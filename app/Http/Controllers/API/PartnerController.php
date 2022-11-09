<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\Partner\PartnerRequest;
use Gentcmen\Models\Partner;

class PartnerController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/partners",
     *     operationId="Fetch partners",
     *     tags={"Partners"},
     *     summary="Fetch partners",
     *     @OA\Response(
     *         response="200",
     *         description="Partners fetched successfully"
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
        $partners = Partner::get();

        return $this->respondOk($partners);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/partners/{partner}",
     *     operationId="Fetch partner by id",
     *     tags={"Partners"},
     *     summary="Fetch partner by id",
     *     @OA\Parameter(
     *         name="partner",
     *         in="path",
     *         description="partner id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Partner fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param Partner $partner
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(Partner $partner): \Illuminate\Http\JsonResponse
    {
        return $this->respondOk($partner);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/partners",
     *     operationId="Create partners",
     *     tags={"Partners"},
     *     summary="Create partners",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Partners is created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/PartnerVirtualData")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(PartnerRequest $request): \Illuminate\Http\JsonResponse
    {
        Partner::create($request->validated());

        return $this->respondCreated();
    }

    /**
     * @OA\Put (
     *     path="/api/v1/admin/partners/{partner}",
     *     operationId="Update the partners",
     *     tags={"Partners"},
     *     summary="Update the partners",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="partner",
     *         in="path",
     *         description="partner id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Partners is created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/PartnerVirtualData")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(PartnerRequest $request, Partner $partner): \Illuminate\Http\JsonResponse
    {
        $partner->update($request->validated());

        return $this->respondOk();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/partners",
     *     operationId="Delete some partner items",
     *     tags={"Partners"},
     *     summary="Delete some partner items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Partners were deleted successfully"
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
        Partner::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/partners/{partner}",
     *     operationId="Delete partner by id",
     *     tags={"Partners"},
     *     summary="Delete partner by id",
     *     @OA\Parameter(
     *         name="partner",
     *         in="path",
     *         description="partner id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Partner deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param Partner $partner
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(Partner $partner): \Illuminate\Http\JsonResponse
    {
        $partner->delete();

        return $this->respondNoContent();
    }
}
