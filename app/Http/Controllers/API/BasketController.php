<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Bucket\StoreItemToBucketRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BasketController extends ApiController
{
     /**
     * @OA\Get(
     *     path="/api/v1/client/baskets",
     *     operationId="Fetch bucket data",
     *     tags={"Baskets"},
     *     summary="Fetch bucket data",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Basket data fetched successfully"
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
        $bucketData = Basket::where('user_id', auth()->id())
	    ->with('product')
	    ->get();

        return $this->respondOk($bucketData);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/baskets/identifiers",
     *     operationId="Fetch bucket identifiers",
     *     tags={"Baskets"},
     *     summary="Fetch bucket identifiers",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="Basket identifiers fetched successfully"
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

    public function basketIdentifiers(): \Illuminate\Http\JsonResponse
    {
        $bucketIdentifiers = Basket::where('user_id', auth()->id())
	    ->with('product')
	    ->select('id', 'product_id', 'qty')
            ->get();

        return $this->respondOk($bucketIdentifiers);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/baskets",
     *     operationId="Store bucket data. Accept array of items",
     *     tags={"Baskets"},
     *     summary="Store bucket data",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="Basket data stored successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreItemsToBucketVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(StoreItemToBucketRequest $request): \Illuminate\Http\JsonResponse
    {

        $insertArr = [];
        foreach ($request->bucket_items as $bucketItem) {
	    $bucketItem['user_id'] = auth()->id();
	    $created_element = Basket::create($bucketItem);
        
            array_push($insertArr, ['bucket_id' => $created_element->id, 'product_id' => $created_element->product_id]);
        }

        return $this->respondOk($insertArr);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/client/baskets/{id}",
     *     operationId="Update bucket item by id",
     *     tags={"Baskets"},
     *     summary="Update bucket item by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="id",
     *          description="Basket item id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Basket item updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Basket item not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateItemInBucketVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, Basket $bucket): \Illuminate\Http\JsonResponse
    {

    //    $this->authorize('update-bucket');

        $bucket->update([
            'qty' => $request->qty
        ]);

        return $this->respondOk(['id' => $bucket->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/baskets",
     *     operationId="Delete bucket items by id",
     *     tags={"Baskets"},
     *     summary="Delete bucket items by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Basket items deleted successfully"
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

    public function destroy(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        Basket::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/baskets/clearAll",
     *     operationId="Delete all bucket items",
     *     tags={"Baskets"},
     *     summary="Delete all bucket items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="All bucket items deleted successfully"
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

    public function clearAll(): \Illuminate\Http\JsonResponse
    {
        Basket::where('user_id', auth()->id())->delete();

        return $this->respondNoContent();
    }
}
