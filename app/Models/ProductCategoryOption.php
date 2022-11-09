<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryOption extends Model
{
    use HasFactory;

    protected $table = 'product_category_options';

    protected $fillable = ['option_name', 'product_category_id'];

    public function productCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public  function  productCategoryOptionValues(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCategoryOptionValue::class, 'option_id');
    }
}
