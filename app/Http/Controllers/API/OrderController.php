<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class OrderController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/admin/orders",
     *     operationId="Fetch orders",
     *     tags={"Orders"},
     *     summary="Fetch orders",
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
     *     @OA\Parameter(
     *          name="sort_by",
     *          description="Sort by date, asc|desc",
     *          in="query",
     *          example="asc",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="order_status_id",
     *          description="Sort by order status id (ids). Can be string or array of ids status",
     *          in="query",
     *          example=1,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="billing_email",
     *          description="Search by billing email address",
     *          in="query",
     *          example="billing_email",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="billing_phone",
     *          description="Search by billing phone number",
     *          in="query",
     *          example="+380XXXXXXX",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="User's orders fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        //$query = Order::with('user', 'orderStatus');
       $query = app(Pipeline::class)
            ->send(Order::query())
            ->through([
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\SortBy::class,
		//\Gentcmen\Http\Controllers\API\OrderQueryFilters\OrderBy::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\OrderStatusId::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\BillingEmail::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\BillingPhone::class,
            ])
            ->thenReturn();

        $orders = $query->with(['user', 'orderStatus', 'orderProducts'])->paginate(10);

        return $this->respondOk($orders);
    }

    public function indexUser(Request $request): \Illuminate\Http\JsonResponse
    {
        //$query = Order::with('user', 'orderStatus');
       $query = app(Pipeline::class)
            ->send(Order::query())
            ->through([
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\SortBy::class,
		//\Gentcmen\Http\Controllers\API\OrderQueryFilters\OrderBy::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\OrderStatusId::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\BillingEmail::class,
                \Gentcmen\Http\Controllers\API\OrderQueryFilters\BillingPhone::class,
            ])
            ->thenReturn();

        $orders = $query->with([ 'orderStatus', 'orderProducts'])->where('user_id', auth()->id())->paginate(10);

        return $this->respondOk($orders);
    }


    /**
     * @OA\Patch (
     *     path="/api/v1/admin/orders/{order}",
     *     operationId="Update order by id",
     *     tags={"Orders"},
     *     summary="Update order by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="order",
     *         description="Order id",
     *         in="path",
     *         example=1,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            @OA\Property(
     *              property="order_status_id",
     *              title="Order status id",
     *              description="Status of order",
     *              example=1
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="User order deleted successfully"
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
     * @return \Illuminate\Http\Response
     */

    /***
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchUpdate(Request $request, Order $order): \Illuminate\Http\JsonResponse
    {
        $order->update($request->all());

        return $this->respondOk(['id' => $order->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/orders",
     *     operationId="Delete order by id or by ids of array",
     *     tags={"Orders"},
     *     summary="Delete order by id or by ids of array",
     *     security={ {"bearerAuth": {}} },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DestroySomeItemsVirtualBody")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="User order deleted successfully"
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
     * @return \Illuminate\Http\Response
     */

    /***
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        Order::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
