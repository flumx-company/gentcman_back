<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralProgramStep extends Model
{
    use HasFactory;

    protected $table = 'referral_program_steps';

    protected $fillable = ['name', 'goal', 'reward', 'referral_program_id'];

    /**
     * Get related program
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ReferralProgram::class);
    }

    /**
     * Get completed steps
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    // @Note rethink maybe we need has Many relationship?
    public function userStep(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ReferralProgramUserStep::class, 'step_id');
    }
}
