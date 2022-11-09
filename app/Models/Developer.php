<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $table = 'developers';

    protected $fillable = ['position', 'first_name', 'last_name', 'information', 'image_link', 'email', 'resource_link'];

    protected $casts = [
        'information' => 'array'
    ];

    public function coverImage(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DeveloperImage::class);
    }
}
