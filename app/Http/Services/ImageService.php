<?php


namespace Gentcmen\Http\Services;


use Gentcmen\Http\Interfaces\HasCoverImage;
use Illuminate\Contracts\Filesystem\Filesystem as FileSystemInstance;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\Factory as FileSystem;
use Illuminate\Support\Str;
use Illuminate\Contracts\Cache\Repository as Cache;
use Intervention\Image\ImageManager;
use JetBrains\PhpStorm\ArrayShape;
use League\Flysystem\Exception;

class ImageService
{
    protected ImageManager $imageTool;
    protected Cache $cache;
    protected FileSystem $fileSystem;
    private string $storageType = 'public';

    public function __construct(ImageManager $imageTool, FileSystem $fileSystem, Cache $cache)
    {
        $this->imageTool = $imageTool;
        $this->fileSystem = $fileSystem;
        $this->cache = $cache;
    }

    public function setStorageType(string $storageType): static
    {
        $this->storageType = $storageType;
        return $this;
    }

    /**
     * Get the storage that will be used for storing images.
     */
    protected function getStorage(): FileSystemInstance
    {
        return $this->fileSystem->disk($this->storageType);
    }

    /**
     * Saves a new image from an upload.
     * @return mixed
     */
    public function saveNewFromUpload(?UploadedFile $uploadedFile, string $type): mixed
    {
        if (!$uploadedFile) return [];
        $imageName = $uploadedFile->getClientOriginalName();
        $imageData = file_get_contents($uploadedFile->getRealPath());

        return $this->saveNew($imageName, $imageData, $type);
    }

    /**
     * Save a new image into storage.
     * @throws Exception
     */
    #[ArrayShape(['name' => "string", 'path' => "string", 'url' => "string"])]
    public function saveNew(string $imageName, string $imageData, string $type): array
    {
        $storage = $this->getStorage();
        $fileName = $this->cleanImageFileName($imageName);

        $imagePath = '/uploads/images/' . $type . '/' . Date('Y-m') . '/';

        $fullPath = $imagePath . $fileName;

        try {
           $storage->put($fullPath, $imageData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return [
            'name' => $imageName,
            'path' => $fullPath,
            'url' => $this->getPublicUrl($fullPath),
        ];
    }

    /**
     * Destroy an image along with its revisions.
     * @throws Exception
     */
    public function destroy(?HasCoverImage $image)
    {
        if ($image) {
            $this->destroyImagesFromPath($image->path);
            $image->delete();
        }
    }

    /**
     * Update the given items' cover image, or clear it.
     * @throws \Exception
     */
    public function updateCoverImage(?HasCoverImage $entity, ?UploadedFile $coverImage, string $type, bool $removeImage = false)
    {
        if ($coverImage) {
            $this->destroy($entity);
            return $this->saveNewFromUpload($coverImage, $type);
        }

        if ($removeImage) {
            $this->destroy($entity);
            return ['success' => true, 'message' => 'Deleted'];
        }

        return ['success' => true, 'message' => 'Nothing to update'];
    }

    /**
     * Destroys an image at the given path.
     * Searches for image thumbnails in addition to main provided path.
     */
    protected function destroyImagesFromPath(string $path): bool
    {
        $storage = $this->getStorage();

        $imageFolder = dirname($path);
        $imageFileName = basename($path);
        $allImages = collect($storage->allFiles($imageFolder));

        // Delete image files
        $imagesToDelete = $allImages->filter(function ($imagePath) use ($imageFileName) {
            return basename($imagePath) === $imageFileName;
        });
        $storage->delete($imagesToDelete->all());

        // Cleanup of empty folders
        $foldersInvolved = array_merge([$imageFolder], $storage->directories($imageFolder));
        foreach ($foldersInvolved as $directory) {
            if ($this->isFolderEmpty($storage, $directory)) {
                $storage->deleteDirectory($directory);
            }
        }

        return true;
    }

    /**
     * Check whether or not a folder is empty.
     */
    protected function isFolderEmpty(FileSystemInstance $storage, string $path): bool
    {
        $files = $storage->files($path);
        $folders = $storage->directories($path);
        return (count($files) === 0 && count($folders) === 0);
    }

    /**
     * Clean up an image file name to be both URL and storage safe.
     */
    protected function cleanImageFileName(string $name): string
    {
        $name = str_replace(' ', '-', $name);
        $nameParts = explode('.', $name);
        $extension = array_pop($nameParts);
        $name = implode('.', $nameParts);
        $name = Str::random(5).Str::slug($name).Str::random(5);

        if (strlen($name) === 0) {
            $name = Str::random(10);
        }

        return $name . '.' . $extension;
    }

    /**
     * Resize image data.
     * @param string $imageData
     * @param int $width
     * @param int $height
     * @param bool $keepRatio
     * @return string
     */
    protected function resizeImage(string $imageData, $width = 220, $height = null, bool $keepRatio = true): string
    {
        try {
            $thumb = $this->imageTool->make($imageData);
        } catch (Exception $e) {
            throw $e;
        }

        if ($keepRatio) {
            $thumb->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $thumb->fit($width, $height);
        }

        $thumbData = (string) $thumb->encode();

        // Use original image data if we're keeping the ratio
        // and the resizing does not save any space.
        if ($keepRatio && strlen($thumbData) > strlen($imageData)) {
            return $imageData;
        }

        return $thumbData;
    }

    /**
     * Get the thumbnail for an image.
     * If $keepRatio is true only the width will be used.
     * Checks the cache then storage to avoid creating / accessing the filesystem on every check.
     * @param HasCoverImage $image
     * @param int $width
     * @param int $height
     * @param bool $keepRatio
     * @return string
     * @throws Exception
     */
    public function getThumbnail(HasCoverImage $image, $width = 220, $height = 220, $keepRatio = false)
    {
        if ($keepRatio && $this->isGif($image)) {
            return $this->getPublicUrl($image->path);
        }

        $thumbDirName = '/' . ($keepRatio ? 'scaled-' : 'thumbs-') . $width . '-' . $height . '/';
        $imagePath = $image->path;
        $thumbFilePath = dirname($imagePath) . $thumbDirName . basename($imagePath);

        if ($this->cache->has('images-' . $image->id . '-' . $thumbFilePath) && $this->cache->get('images-' . $thumbFilePath)) {
            return $this->getPublicUrl($thumbFilePath);
        }

        $storage = $this->getStorage();
        if ($storage->exists($thumbFilePath)) {
            return $this->getPublicUrl($thumbFilePath);
        }

        $thumbData = $this->resizeImage($storage->get($imagePath), $width, $height, $keepRatio);

        $storage->put($thumbFilePath, $thumbData);

        $this->cache->put('images-' . $image->id . '-' . $thumbFilePath, $thumbFilePath, 60 * 60 * 72);

        return $this->getPublicUrl($thumbFilePath);
    }

    /**
     * Checks if the image is a gif. Returns true if it is, else false.
     */
    protected function isGif(HasCoverImage $image): bool
    {
        return strtolower(pathinfo($image->path, PATHINFO_EXTENSION)) === 'gif';
    }

    /**
     * Gets a public facing url for an image by checking relevant environment variables.
     */
    private function getPublicUrl(string $filePath): string
    {
        $storage = config("filesystems.disks.{$this->storageType}.url", "/storage");
        $basePath = url($storage);
        return rtrim($basePath, '/') . $filePath;
    }
}
