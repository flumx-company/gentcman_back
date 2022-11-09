<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\API\ProductQueryFilters\ProductFilter;
use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\Product\CreateProductRequest;
use Gentcmen\Http\Services\ViewService;
use Gentcmen\Models\OrderProduct;
use Gentcmen\Models\Product;
use Gentcmen\Models\ProductCategory;
use Gentcmen\Models\ProductStatus;
use Gentcmen\Models\Review;
use Illuminate\Http\Response;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/products",
     *     operationId="Fetch products by query params",
     *     tags={"Products"},
     *     summary="Fetch products by query params",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="random",
     *         in="query",
     *         description="fetch random records",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *    @OA\Parameter(
     *         name="novelty",
     *         in="query",
     *         description="fetch novelty products",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="promotional_goods",
     *         in="query",
     *         description="fetch promotional goods",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Fetch published products or unpublished",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="identifiers",
     *         in="query",
     *         description="ids of elements",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="order_by",
     *          description="Order by from expensive to cheap or from cheap to expensive 1- desc, 0 - asc",
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="product_category_id",
     *          description="Product category ids",
     *          in="query",
     *          example="1,2",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="width_belt",
     *          description="Product width",
     *          in="query",
     *          example="40,50",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="length_product",
     *          description="Product length",
     *          in="query",
     *          example="100,150",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="gender",
     *          description="Product gender",
     *          in="query",
     *          example="male",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="material",
     *          description="Product material",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="clasp_type",
     *          description="Product clasp type",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="color",
     *          description="Product color",
     *          in="query",
     *          example="blue",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="price",
     *          description="Product price",
     *          in="query",
     *          example="100,400",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Products fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function searchByQueryAdmin(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = ProductFilter::apply($request);
        return $this->respondOk($products);
    }    

    public function searchByQuery(Request $request): \Illuminate\Http\JsonResponse
    {
	if($request->query('oldquery')){
	$products = ProductFilter::apply($request);
        return $this->respondOk($products);
}
	$take = $request->query('limit') ?? 10;
    $published = $request->query('published') ?? 1;
    $costStart = $request->query('costStart');
    $costFinish = $request->query('costFinish');
    $is_discount = $request->query('is_discount');
    $product_status_ids = collect(explode(',', $request->query('product_status_ids')))->filter(function ($item) {
        return !!$item;
    });
    $product_category_id= $request->query('product_category_id');
    $contentQuery = $request->query('contentQuery');
    $contentValue = $request->query('contentValue');
    $sortField = $request->query('sort-field');
$sortBy = $request->query('sort-by');
//dd($costStart , $costFinish, $is_discount, $product_status_ids, $contentQuery, $contentValue);
        $products = Product::where('published', $published)
        ->with('productCategories', 'productStatus', 'discounts')
        ->withCount('reviews')
        ->when(count($product_status_ids) > 0, function ($query) use ($product_status_ids) {
            $query->whereHas('productStatus', function ($query) use ($product_status_ids) {
	    $query->whereIn('id', $product_status_ids);
            });
        })
	->when($product_category_id, function ($query) use ($product_category_id){
	    $query->whereHas('productCategories', function ($query) use ($product_category_id){
        	$query->where('product_category_id', $product_category_id);
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
        })
        ->when($sortField && $sortBy, function ($query) use ($sortField, $sortBy) {
            $query->orderBy($sortField, $sortBy);
         })
        ->paginate($take);
        return $this->respondOk($products);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/products/query/popular-products",
     *     operationId="Fetch popular products",
     *     tags={"Products"},
     *     summary="Fetch popular products",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="limit",
     *          description="Indicate how much you need to take. By default it takes 7 items",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="by_sales",
     *          description="Fetch popular products by sales",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="by_number_reviews",
     *          description="Fetch popular products by the number of reviews",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Popular products fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function fetchPopularProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->query('limit') ?? 7;
	$published = $request->query('published') ?? 1;

        $popularProducts = [];

        if ($request->query('by_sales')) {
            $popularProducts = Product::popularBySales($limit, $request); // TODO
        }

        if ($request->query('by_number_reviews')) {
            $popularProducts = Product::popularByNumberReviews($limit, $request); // TODO
        }

        return $this->respondOk(
	    $popularProducts
//Product::with(['discounts', 'productCategories', 'productStatus'])->get()
);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/products/query/by-categories",
     *     operationId="Fetch products by categories",
     *     tags={"Products"},
     *     summary="Fetch products by categories",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="category_id",
     *          description="category id",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="common_limit",
     *          description="general limit for every category products",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="limit_for_category",
     *          description="take items from specific category",
     *          in="query",
     *          example="",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Products by categories fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function fetchByCategories(Request $request): \Illuminate\Http\JsonResponse
    {
        $category_id = $request->query('category_id');
        $common_limit = $request->query('common_limit') ?? 7;
        $limit_for_category = $request->query('limit_for_category') ?? 7;
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
//dd($sortField, $sortBy);
        $productsByCategories = ProductCategory::with(['products' => function ($query) use ($common_limit, $published, $costStart, $costFinish, $is_discount, $product_status_ids, $contentQuery, $contentValue, $sortField, $sortBy) {
//dd(intval($common_limit) > 7 ? 7 : $common_limit);
        //    $query->limit(intval($common_limit) > 200 ? 200 : $common_limit);
	    $query->with(['productCategories', 'productStatus', 'discounts'])
		->withCount('reviews')
		->where('published', $published)
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
		})
		->when($sortField && $sortBy, function ($query) use ($sortField, $sortBy) {
		    $query->orderBy($sortField, $sortBy);
	        });
        }])
            ->withCount('products')
            ->get();

    //    if ($category_id && $limit_for_category) {
    //        $productsByCategories = $productsByCategories->map(function ($category) use ($category_id, $limit_for_category){
    //            if ($category->id === intval($category_id)) {
                    //$temp = $category->products;
                    //unset($category->products);
                    //$category->products = $temp->take($limit_for_category);
    //                $category->products_count = $category->products->count();
    //            }

    //            return $category;
    //        });
    //    }

        return $this->respondOk($productsByCategories);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/products/{product}",
     *     operationId="Fetch product by id with productCategories, productStatus and product reviews",
     *     tags={"Products"},
     *     summary="Fetch product by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Fetch published or unpublished",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Product fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $product->load(['productCategories', 'productStatus', 'discounts' => function ($q) {
            $q->notExpires();
        }]);

        if (in_array($request->query('published'), ["1", "0"]))
        {
            $product = $product->published === intval($request->query('published')) ? $product : null;
        }

    //    if (auth('api')->check()) {
    //        app(ViewService::class)->add($product);
    //    }

        return $this->respondOk($product);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/products",
     *     operationId="Create product",
     *     tags={"Products"},
     *     summary="Create product",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Product is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product status not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *          mediaType="multipart/form-data",
     *              @OA\Schema(
     *                type="object",
     *                ref="#/components/schemas/CreateProductVirtualBody"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductRequest $request)
    {
        $productStatus = ProductStatus::find($request->status_id);

        if (!$productStatus) {
            return $this->respondNotFound();
        }

        return DB::transaction(function () use ($request) {
            $product = Product::create([
                'name' => $request->name,
                'cost' => $request->cost,
                'amount' => $request->amount,
                'description' => $request->description,
                'content' => gettype($request->content) == 'string' ?  json_decode($request->content) : $request->content,
                'images_content' => $request->images_content,
                'product_status_id' => $request->status_id,
		'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_description,
                'banner_image' => $request->banner_image,
		'product_info_image' => $request->product_info_image,
                'published' => boolval($request->published ?? false),
            ]);

            foreach ($request->category_ids as $categoryId) {
                $product->productCategories()->attach($categoryId);
            }

            return $this->respondCreated(['id' => $product->id]);
        });
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/products/{product}/discount",
     *     operationId="Create discount",
     *     tags={"Products"},
     *     summary="Create discount",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Discount is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateDiscountVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function createDiscount(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $expires_at_value = today()->addDays(7);

        if ($request->expires_at)
        {
            $expires_at_value = Carbon::make($request->expires_at);
        }

        $discount = $product->createDiscount($request->amount, $expires_at_value);

        return $this->respondCreated(['id' => $discount->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/products",
     *     operationId="Delete some product items",
     *     tags={"Products"},
     *     summary="Delete some product items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Products were deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DestroySomeItemsVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DestroyItemsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        Product::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/products/{product}",
     *     operationId="Update product",
     *     tags={"Products"},
     *     summary="Update product",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product not found"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/UpdateProductVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateProductRequest $request, Product $product)
    {
        $productStatus = ProductStatus::find($request->status_id);

        if (!$productStatus) {
            return $this->respondNotFound('Product status');
        }


        return DB::transaction(function () use ($request, $product) {
            $product->update([
                'name' => $request->name,
                'cost' => $request->cost,
                'amount' => $request->amount,
                'description' => $request->description,
                'content' => $request->content,
                'product_status_id' => $request->status_id,
                'images_content' => $request->images_content,
		        'meta_title' => $request->meta_title,
	            'meta_description' => $request->meta_description,
	            'meta_keywords' => $request->meta_description,
		'product_info_image' => $request->product_info_image,
                'published' => $request->published ?? false,
                'banner_image' => $request->banner_image,
            ]);

            $productCategoryIds = ProductCategory::select('id')->get()->pluck('id')->toArray();

            foreach($request->category_ids as $key => $categoryId) {
                if (!$product->productCategories()->get()->contains($categoryId)) {
                    $product->productCategories()->attach($categoryId, ['product_id' => $product->id]);
                } else {
                    $product->productCategories()->updateExistingPivot($categoryId, [
                        'product_category_id' => $categoryId,
                    ]);
                }

                if (($key = array_search($categoryId, $productCategoryIds)) !== false) {
                    unset($productCategoryIds[$key]);
                }
            }

            $product->productCategories()->detach(array_values($productCategoryIds));

            return $this->respondOk(['id' => $product->id]);
        });
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/products/{product}",
     *     operationId="Delete product by id",
     *     tags={"Products"},
     *     summary="Delete product by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="product",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        $product->delete();
        return $this->respondNoContent();
    }
}
