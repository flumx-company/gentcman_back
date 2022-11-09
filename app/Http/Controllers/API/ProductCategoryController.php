<?php

namespace Gentcmen\Http\Controllers\API;


use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\ProductCategories\CreateProductCategoryRequest;
use Gentcmen\Http\Requests\ProductCategories\UpdateProductCategoryRequest;
use Gentcmen\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductCategoryController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/product-categories",
     *     operationId="Fetch product categories",
     *     tags={"Product Categories"},
     *     summary="Fetch product categories",
     *     @OA\Response(
     *         response="200",
     *         description="Product category fetched successfully"
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

    public function index(): \Illuminate\Http\JsonResponse
    {
        $productCategories = ProductCategory::with(['productCategoryOptions', 'discounts'])
            ->withCount('products')
            ->get();

        return $this->respondOk($productCategories);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/product-categories/{productCategory}",
     *     operationId="Fetch product category by id",
     *     tags={"Product Categories"},
     *     summary="Fetch product category by id",
     *     @OA\Parameter(
     *          name="productCategory",
     *          description="Product category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product category fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product category not found"
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

    public function getById(ProductCategory $productCategory): \Illuminate\Http\JsonResponse
    {
        $productCategory = $productCategory
            ->load('productCategoryOptions.productCategoryOptionValues')
            ->loadCount('products');

        return $productCategory ? $this->respondOk($productCategory) : $this->respondNotFound();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/product-categories",
     *     operationId="Create product category",
     *     tags={"Product Categories"},
     *     summary="Create product category",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Product status is created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductCategoryVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateProductCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $productCategory = ProductCategory::create([
            'name' => $request->name,
	    'image_link' => $request->image_link,
	    'description' => $request->description

        ]);

        return $this->respondOk(['id' => $productCategory->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/product-categories/{category}",
     *     operationId="Update product category",
     *     tags={"Product Categories"},
     *     summary="Update product category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="category",
     *          description="Product category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Product status is updated successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductCategoryVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category): \Illuminate\Http\JsonResponse
    {
        $category->update([
            'name' => $request->name,
	    'image_link' => $request->image_link,
	    'description' => $request->description
        ]);

        return $this->respondOk(['id' => $category->id]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/product-categories/{productCategory}/discount",
     *     operationId="Create discount",
     *     tags={"Product Categories"},
     *     summary="Create discount",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="productCategory",
     *          description="Product category id",
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

    public function createDiscount(Request $request, ProductCategory $productCategory): \Illuminate\Http\JsonResponse
    {
        $expires_at_value = today()->addDays(7);

        if ($request->expires_at)
        {
            $expires_at_value = Carbon::make($request->expires_at);;
        }

        $discount = $productCategory->createDiscount($request->amount, $expires_at_value);
        return $this->respondCreated($discount);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/product-categories",
     *     operationId="Delete some product category items",
     *     tags={"Product Categories"},
     *     summary="Delete some product category items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Product category items deleted successfully"
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        ProductCategory::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/product-categories/{category}",
     *     operationId="Delete product category by id",
     *     tags={"Product Categories"},
     *     summary="Delete product category by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="category",
     *          description="Product category id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Product category deleted successfully"
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
    public function destroy(ProductCategory $category): \Illuminate\Http\JsonResponse
    {
        $category->delete();

        return $this->respondNoContent();
    }
}
