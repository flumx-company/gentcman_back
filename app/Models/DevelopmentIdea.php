<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevelopmentIdea extends Model
{
    use HasFactory;

    protected $table = 'development_ideas';

    protected  $fillable = [
        'user_name',
        'email',
        'idea',
	'theme',
        'status',
        'message',
        'user_id'
    ];
}
