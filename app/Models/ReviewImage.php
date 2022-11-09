<?php

namespace Gentcmen\Models;

use Gentcmen\Http\Interfaces\HasCoverImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model implements HasCoverImage
{
    use HasFactory;

    protected $table = 'review_images';

    protected $fillable = ['name', 'url', 'path'];

    public function cover(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function coverImageTypeKey(): string
    {
        return 'review_image';
    }
}
