<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMessage extends Model
{
    use HasFactory;

    protected $table = 'template_messages';

    protected $fillable = [
        'type',
        'items'
    ];

    protected $casts = [
      'items' => 'array'
    ];
}
