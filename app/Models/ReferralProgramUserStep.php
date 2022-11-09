<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralProgramUserStep extends Model
{
    use HasFactory;

    protected $table = 'referral_program_user_steps';

    protected $fillable = ['program_id', 'step_id', 'user_id', 'completed'];
}
