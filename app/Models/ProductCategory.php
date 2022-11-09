<?php

namespace Gentcmen\Models;

use Gentcmen\Traits\Discountable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory, Discountable;

    protected $table = 'product_categories';

    protected $fillable = ['name', 'image_link', 'description'];

    /**
     * Get products of the product category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories_linked');
    }

    public  function productCategoryOptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductCategoryOption::class)->with(['productCategoryOptionValues']);
    }

    /**
     * Get the product category's image.
     */
    public function coupon(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Coupon::class, 'couponable');
    }
}
