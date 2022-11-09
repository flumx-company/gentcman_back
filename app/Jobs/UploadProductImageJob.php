<?php

namespace Gentcmen\Jobs;

use Gentcmen\Mail\SendRecoverCodeMail;
use Gentcmen\Models\ReportProblemsImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UploadProductImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;
    protected $imagePath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productId, $imagePath)
    {
        $this->productId = $productId;
        $this->imagePath = $imagePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $filename = explode('/', $this->imagePath);
        // $pathToFile = storage_path('app/public/'.implode('/', array_slice($filename, 2)));
        $pathToFile = Storage::path($this->imagePath);

        $image_url = cloudinary()->upload($pathToFile)->getSecurePath();
        ReportProblemsImage::create([
            'product_id' => $this->productId,
            'image_url' => $image_url,
        ]);

        Storage::delete($this->imagePath);
        // unlink($pathToFile);
    }
}
