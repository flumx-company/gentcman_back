<?php

namespace Gentcmen\Models;

use Gentcmen\Events\ProductRedeemedEvent;
use Gentcmen\Traits\Discountable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory, Discountable;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'cost',
        'amount',
        'description',
        'content',
        'product_status_id',
        'banner_image',
	'product_info_image',
        'images_content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published',
    ];

    public $timestamps = true;

    protected $casts = [
        'images_content' => 'array',
        'content' => 'array',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'redeemed' => ProductRedeemedEvent::class,
    ];

    protected $appends = ['avg_rating'];

    /**
     * Fetch popular products by sales
     * @param $query
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */

    public function scopePopularBySales($query, int $limit = 7, $request)
    {
$published = $request->query('published') ?? 1;
$costStart = $request->query('costStart');
$costFinish = $request->query('costFinish');
$is_discount = $request->query('is_discount');
$product_status_ids = collect(explode(',', $request->query('product_status_ids')))->filter(function ($item) {
    return !!$item;
});
$contentQuery = $request->query('contentQuery');
$contentValue = $request->query('contentValue');
$sortField = $request->query('sort-field');
$sortBy = $request->query('sort-by');
//dd($costStart , $costFinish, $is_discount, $product_status_ids, $contentQuery, $contentValue);


        $e = OrderProduct::with(['product' => function ($query) use ($published, $costStart, $costFinish, $is_discount, $product_status_ids, $contentQuery, $contentValue, $sortField, $sortBy) {
                $query
		    ->with(['discounts', 'productCategories', 'productStatus'])
		    ->withCount('productOrders')
		    ->when($published, function ($thenQuery) use ($published) {
			$thenQuery->where('published', $published);
		    })
		    ->when(count($product_status_ids) > 0, function ($query) use ($product_status_ids) {
        $query->whereHas('productStatus', function ($query) use ($product_status_ids) {
    $query->whereIn('id', $product_status_ids);
        });
    })
    ->when($is_discount, function ($query) {
        $query->has('discounts')->orHas('productCategories.discounts');
    })
    ->when($contentQuery && $contentValue, function ($query) use ($contentQuery, $contentValue){
       $query->where($contentQuery, $contentValue);
    })
    ->when($costStart && $costFinish, function ($query) use ($costStart, $costFinish) {
      $query->whereBetween('cost', [$costStart, $costFinish]);
  });

            }])
            ->groupBy('product_id')
            ->selectRaw('count(*) as total, product_id')
            ->orderByDesc('total')
            ->take($limit)
            ->get()
            ->pluck('product');

	if($sortBy && $sortField) {
	    if($sortBy == 'asc') {
		$e = $e->sortBy($sortField);
	    }
	    if($sortBy == 'desc') {
		$e = $e->sortByDesc($sortField);
	    }
	}

	return $e->filter(function ($value) { return !is_null($value); })
	    ->values();

    }

    /**
     * Fetch popular products by number of reviews
     * @param $query
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */

    public function scopePopularByNumberReviews($query, int $limit = 7, $request)
    {
$published = $request->query('published') ?? 1;
$costStart = $request->query('costStart');
$costFinish = $request->query('costFinish');
$is_discount = $request->query('is_discount');
$product_status_ids = collect(explode(',', $request->query('product_status_ids')))->filter(function ($item) {
    return !!$item;
});
$contentQuery = $request->query('contentQuery');
$contentValue = $request->query('contentValue');
$sortField = $request->query('sort-field');
$sortBy = $request->query('sort-by');

        $e = Review::with(['product' => function ($query) use ($published, $costStart, $costFinish, $is_discount, $product_status_ids, $contentQuery, $contentValue, $sortField, $sortBy)  {
            $query
		->with(['discounts', 'productCategories', 'productStatus'])
		->withCount('reviews')
		->when($published, function ($thenQuery) use ($published) {
		    $thenQuery->where('published', $published);
		 })->when(count($product_status_ids) > 0, function ($query) use ($product_status_ids) {
        $query->whereHas('productStatus', function ($query) use ($product_status_ids) {
    $query->whereIn('id', $product_status_ids);
        });
    })
    ->when($is_discount, function ($query) {
        $query->has('discounts')->orHas('productCategories.discounts');
    })
    ->when($contentQuery && $contentValue, function ($query) use ($contentQuery, $contentValue){
       $query->where($contentQuery, $contentValue);
    })
    ->when($costStart && $costFinish, function ($query) use ($costStart, $costFinish) {
      $query->whereBetween('cost', [$costStart, $costFinish]);
  });

        }])
            ->groupBy('product_id')
            ->selectRaw('count(*) as total, product_id')
            ->orderByDesc('total')
            ->take($limit)
            ->get()
            ->pluck('product');

	if($sortBy && $sortField) {
    if($sortBy == 'asc') {
$e = $e->sortBy($sortField);
    }
    if($sortBy == 'desc') {
$e = $e->sortByDesc($sortField);
    }
}

return $e->filter(function ($value) { return !is_null($value); })
    ->values();

    }

    public function productStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductStatus::class);
    }

    public function productCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_categories_linked');
    }

    /**
     * Get the reviews of the product.
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }


    public function getAvgRatingAttribute()
    {
	return floor($this->reviews()->avg('rating') ?? 0);
    }

    /**
     * Get the user that added the product.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product's view history.
     */
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Get product orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function productOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Get the product's image.
     */
    public function coupon(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Coupon::class, 'couponable');
    }
}
