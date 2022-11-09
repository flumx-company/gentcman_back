<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReportProblem extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['theme', 'content', 'user_email', 'message', 'status'];

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportProblemsImage::class, 'report_problems_id', 'id');
    }
}
