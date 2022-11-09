<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'views', 'created_at'];

    /**
     * Get the parent viewable model.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */

    public function viewable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get related product
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function product(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
	    return $this->hasOne(Product::class, 'id', 'viewable_id')
                ->with(['discounts', 'productCategories', 'productStatus']);
    }
}
