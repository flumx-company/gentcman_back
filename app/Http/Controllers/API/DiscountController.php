<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends ApiController
{
    /**
     * @OA\Put(
     *     path="/api/v1/admin/discounts/{discount}",
     *     operationId="Update discount by id",
     *     tags={"Discounts"},
     *     summary="Update discount by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="discount",
     *          description="Discount id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Discount is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
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
     * @param Request $request
     * @param int $discountId
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateDiscount(Request $request, Discount $discount): \Illuminate\Http\JsonResponse
    {
        $discount->update([
            'amount' => $request->amount,
            'expires_at' => $request->expires_at ?: $discount->expires_at
        ]);

        return $this->respondOk(['id' => $discount->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/discounts/{id}",
     *     operationId="Delete discount by id",
     *     tags={"Discounts"},
     *     summary="Delete discount by id for product",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="discountId",
     *          description="Discount id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Discount is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function removeDiscount(Discount $discount): \Illuminate\Http\JsonResponse
    {
        $discount->delete();
        return $this->respondNoContent();
    }
}
