<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
	'link_desktop',
	'link_mobile',
	'description',
	'button_link',
	'button_text'
    ];

    protected $guarded = ['published'];
}
