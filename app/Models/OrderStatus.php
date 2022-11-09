<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    public const STATUS_CREATED = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_PROCESSED = 4;
    public const STATUS_DENIED = 5;
    public const STATUS_REFUNDED = 6;

    protected $fillable = ['name', 'color', 'background', 'border'];
}
