<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Model;

class FaqSubCategory extends Model
{
    protected $table = 'faq_subcategories';

    protected $fillable = ['question', 'answer', 'faq_category_id'];

    public function faqCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FaqCategory::class);
    }
}
