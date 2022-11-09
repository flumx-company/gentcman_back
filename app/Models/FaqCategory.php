<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FaqCategory extends Model
{
    use Notifiable;

    protected $table = 'faq_categories';

    protected $fillable = ['id', 'name'];

    public function faqSubCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FaqSubCategory::class);
    }
}
