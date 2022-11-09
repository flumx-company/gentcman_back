<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralProgram extends Model
{
    use HasFactory;

    protected $table = 'referral_programs';

    protected $fillable = ['name', 'uri', 'lifetime_minutes', 'reward'];

    /**
     * Get referral program steps
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function programSteps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReferralProgramStep::class);
    }
}
