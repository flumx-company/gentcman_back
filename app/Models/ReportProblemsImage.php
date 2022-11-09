<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportProblemsImage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'image_url', 'report_problems_id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
