<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Coupon\CreateCouponRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\Coupon;
use Carbon\Carbon;
use Gentcmen\Models\Product;
use Gentcmen\Models\ProductCategory;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/coupons",
     *     operationId="Fetch available coupons",
     *     tags={"Coupons"},
     *     summary="Fetch available coupons",
     *     @OA\Parameter(
     *         name="not_expired",
     *         in="query",
     *         description="Fecth not expired records",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="with_trashed",
     *         in="query",
     *         description="Fecth only trashed items",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="only_trashed",
     *         in="query",
     *         description="Fecth only trashed items",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Coupons data fetched successfully"
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
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Coupon::query();

        if ($request->query('not_expired'))
        {
            $query = $query->notExpired();
        }

        if ($request->query('with_trashed'))
        {
            $query = $query->withTrashed();
        }

        if ($request->query('only_trashed'))
        {
            $query = $query->onlyTrashed();
        }

        $coupons = $query->paginate(10);

        return $this->respondOk($coupons);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/coupons/{coupon}",
     *     operationId="Fetch coupon by id",
     *     tags={"Coupons"},
     *     summary="Fetch coupon by id",
     *     @OA\Parameter(
     *          name="coupon",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Coupon fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Coupon not found"
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

    public function getById(Coupon $coupon): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($coupon);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/coupons",
     *     operationId="Store a coupon",
     *     tags={"Coupons"},
     *     summary="Store a coupon",
     *     security={ {"bearer": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Coupon created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CouponVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreateCouponRequest $request): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'name' => $request->name,
            'cost' => $request->cost,
            'discount' => $request->discount,
            'expires_at' => $request->expires_at ?: Carbon::today()->addDays(7)
        ];

        if ($request->entity_type === (new \ReflectionClass(Product::class))->getShortName())
        {
            $model = Product::find($request->entity_id);
            $model->coupon()->create($payload);
        }
        else if ($request->entity_type === (new \ReflectionClass(ProductCategory::class))->getShortName())
        {
            $model = ProductCategory::find($request->entity_id);
            $model->coupon()->create($payload);
        }
        else {
            Coupon::create($payload);
        }

        return $this->respondCreated();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/coupons/{coupon}",
     *     operationId="Update a coupon",
     *     tags={"Coupons"},
     *     summary="Update a coupon",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *          name="coupon",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Coupon updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Coupon not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CouponVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(CreateCouponRequest $request, Coupon $coupon): \Illuminate\Http\JsonResponse
    {
        $coupon->update([
            'name' => $request->name,
            'cost' => $request->cost,
            'discount' => $request->discount,
            'expires_at' => $request->expires_at ?: Carbon::today()->addDays(7)
        ]);

        return $this->respondOk(['id' => $coupon->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/coupons/{coupon}",
     *     operationId="Delete a coupon",
     *     tags={"Coupons"},
     *     summary="Delete a coupon",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *          name="coupon",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Coupon deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Coupon not found"
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

    public function destroy(Coupon $coupon): \Illuminate\Http\JsonResponse
    {
        $coupon->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/coupons",
     *     operationId="Remove coupons",
     *     tags={"Coupons"},
     *     summary="Remove coupons",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Coupons removed successfully"
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
        Coupon::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/admin/coupons/restore/{id}",
     *     operationId="Restore a coupon",
     *     tags={"Coupons"},
     *     summary="Restore a coupon",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *          name="id",
     *          description="Coupon id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Coupon restored successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Coupon not found"
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

    public function restore(int $id): \Illuminate\Http\JsonResponse
    {
        $coupon = Coupon::onlyTrashed()->find($id);

        $coupon->restore();

        return $this->respondOk($coupon);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/coupons/soft-delete/permanent-delete",
     *     operationId="Remove permanent coupons",
     *     tags={"Coupons"},
     *     summary="Remove permanent coupons",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Coupons removed successfully"
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

    public function permanentDeleteSoftDeleted(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
//	dd(Coupon::onlyTrashed()->get()->toArray());
        Coupon::onlyTrashed()->find($request->ids)->each(function ($coupon) {
	    //dd($coupon);
            $coupon->forceDelete();
        });;

        return $this->respondNoContent();
    }
}
