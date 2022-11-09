<?php

namespace Gentcmen\Http\Controllers\API;


use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\ProductStatuses\CreateProductStatusRequest;
use Gentcmen\Models\ProductStatus;
use Illuminate\Http\Request;

class ProductStatusController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/product-statuses",
     *     operationId="Fetch product statuses",
     *     tags={"Product Statuses"},
     *     summary="Fetch product statuses",
     *     @OA\Response(
     *         response="200",
     *         description="Product statuses fetched successfully"
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
        $productStatuses = ProductStatus::all();

        return $this->respondOk($productStatuses);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/product-statuses/{status}",
     *     operationId="Fetch product statuses by id",
     *     tags={"Product Statuses"},
     *     summary="Fetch product statuses by id",
     *     @OA\Parameter(
     *          name="status",
     *          description="Product status id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product statuses fetched successfully"
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

    public function getById(Request $request , ProductStatus $status): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($status);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/product-statuses",
     *     operationId="Create product status",
     *     tags={"Product Statuses"},
     *     summary="Create product status",
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductStatusVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProductStatusRequest $request): \Illuminate\Http\JsonResponse
    {
        $productStatus = ProductStatus::create([
            'name' => $request->name,
        ]);
        return $this->respondCreated(['id' => $productStatus->id]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/product-statuses/{status}",
     *     operationId="Uodate product status by id",
     *     tags={"Product Statuses"},
     *     summary="Update product status by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="status",
     *          description="Product status id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Product status updated successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductStatusVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateProductStatusRequest $request, ProductStatus $status): \Illuminate\Http\JsonResponse
    {
        $status->update([
            'name' => $request->name
        ]);

        return $this->respondOk(['id' => $status->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/product-statuses",
     *     operationId="Delete some product status items",
     *     tags={"Product Statuses"},
     *     summary="Delete some product status items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Product status items deleted successfully"
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
        ProductStatus::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/product-statuses/{status}",
     *     operationId="Delete product statuses by id",
     *     tags={"Product Statuses"},
     *     summary="Delete product statuses by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="status",
     *          description="Product status id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Product statuses deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(ProductStatus $status): \Illuminate\Http\JsonResponse
    {
        $status->delete();

        return $this->respondNoContent();
    }
}
