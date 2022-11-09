<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaImprovement extends Model
{
    use HasFactory;

    protected $table = 'idea_improvements';

    protected $fillable = [
        'user_name',
        'email',
        'improvement',
        'user_id',
        'status',
        'message',
        'theme',
    ];
}
