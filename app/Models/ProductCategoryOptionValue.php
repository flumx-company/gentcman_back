<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryOptionValue extends Model
{
    use HasFactory;

    protected $table = 'product_category_option_values';

    protected $fillable = ['value', 'option_id'];

    public function productCategoryOption(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategoryOption::class);
    }
}
