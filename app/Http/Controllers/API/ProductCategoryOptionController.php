<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\ProductCategoryOption\CreateProductCategoryOptionRequest;
use Gentcmen\Models\ProductCategoryOption;
use Gentcmen\Models\ProductCategoryOptionValue;
use Illuminate\Http\Response;

class ProductCategoryOptionController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/product-category-options/{productCategoryOption}",
     *     operationId="Fetch product category option by id",
     *     tags={"Product Category Options"},
     *     summary="Fetch product category option by id",
     *     @OA\Parameter(
     *          name="productCategoryOption",
     *          description="Option id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product category option fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product category option not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param ProductCategoryOption $productCategoryOption
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(ProductCategoryOption $productCategoryOption): \Illuminate\Http\JsonResponse
    {
        $productCategoryOption = $productCategoryOption->load('productCategoryOptionValues');

        return $this->respondOk($productCategoryOption);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/product-categories/{categoryId}/options",
     *     operationId="Create product",
     *     tags={"Product Categories"},
     *     summary="Create product category filter option",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="categoryId",
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
     *         description="Product category filter option is created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductCategoryOptionVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateProductCategoryOptionRequest $request
     * @param int $categoryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductCategoryOptionRequest $request, int $categoryId)
    {
        $productCategoryOption = ProductCategoryOption::create([
            'option_name' => $request->option_name,
            'product_category_id' => $categoryId
        ]);

        if (count($request->option_values) > 0)
        {
            foreach ($request->option_values as $item) {
                $productCategoryOptionValue = new ProductCategoryOptionValue;
                $productCategoryOptionValue->value = $item['value'];

                $productCategoryOption->productCategoryOptionValues()->save($productCategoryOptionValue);
            }
        }

        return $this->respondCreated(['id' => $productCategoryOption->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/product-category-options/{option}",
     *     operationId="Update product category option",
     *     tags={"Product Category Options"},
     *     summary="Update product category filter option",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="option",
     *          description="Product category option id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product category option is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product category option not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductCategoryOptionVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateProductCategoryOptionRequest $request
     * @param ProductCategoryOption $option
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateProductCategoryOptionRequest $request, ProductCategoryOption $option)
    {
        $option->update([
            'option_name' => $request->option_name,
        ]);

        if (count($request->option_values) > 0)
        {
            $option->productCategoryOptionValues()->delete();
            foreach ($request->option_values as $item) {
                $productCategoryOptionValue = new ProductCategoryOptionValue;
                $productCategoryOptionValue->value = $item['value'];
                $option->productCategoryOptionValues()->save($productCategoryOptionValue);
            }
        }

        return $this->respondOk(['id' => $option->id]);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/product-category-options/{option}",
     *     operationId="Destroy product category option",
     *     tags={"Product Category Options"},
     *     summary="Destroy product category option",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="option",
     *          description="Product category option id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Product category option is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product category option not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategoryOption $option
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(ProductCategoryOption $option): \Illuminate\Http\JsonResponse
    {
        $option->delete();

        return $this->respondNoContent();
    }
}
