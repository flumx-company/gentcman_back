<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'product_id', 'user_id'];

    public $timestamps = true;

    /**
     * Get owner of favorite item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class)->with(['discounts', 'productCategories', 'productStatus']);
    }
}
