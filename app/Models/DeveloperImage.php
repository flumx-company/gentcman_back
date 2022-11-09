<?php

namespace Gentcmen\Models;

use Gentcmen\Facades\Images;
use Gentcmen\Http\Interfaces\HasCoverImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeveloperImage extends Model implements HasCoverImage
{
    use HasFactory;

    protected $table = 'developer_images';

    protected $fillable = ['name', 'url', 'path'];

    /**
     * Get a thumbnail for this image.
     * @param  int $width
     * @param  int $height
     * @param bool|false $keepRatio
     * @return string
     */
    public function getThumb(string $storageType, $width, $height, $keepRatio = false)
    {
        return Images::getThumbnail($this, $storageType, $width, $height, $keepRatio);
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function coverImageTypeKey(): string
    {
        return 'developer_images';
    }
}
