<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    protected $table = 'password_resets';

    protected $fillable = ['email', 'token', 'expires_at'];

    public $timestamps = false;

    protected $dates = ['expires_at'];

    public function setUpdatedAtAttribute($value) {
        $this->attributes['created_at'] = \Carbon\Carbon::now();
    }
}
