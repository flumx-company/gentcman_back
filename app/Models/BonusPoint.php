<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusPoint extends Model
{
    use HasFactory;

    protected $table = 'bonus_points';

    protected $fillable = ['points', 'user_id'];

    /**
     * Get related user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
