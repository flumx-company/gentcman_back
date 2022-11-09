<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostOffer extends Model
{
    use HasFactory;

    protected $table = 'post_offers';

    protected $fillable = [
        'theme',
        'text',
        'user_name',
        'email',
        'status',
        'message',
        'user_id',
    ];
}
