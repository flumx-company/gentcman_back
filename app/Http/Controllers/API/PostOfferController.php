<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\PostOffer\CreatePostOfferRequest;
use Gentcmen\Http\Requests\PostOffer\PatchUpdatePostOfferRequest;
use Gentcmen\Models\PostOffer;
use Gentcmen\Models\User;
use Illuminate\Support\Facades\Notification;
use Gentcmen\Notifications\PostOfferNotification;

class PostOfferController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/client/post-offers",
     *     operationId="Fetch user's post offers",
     *     tags={"Post offers"},
     *     summary="Fetch user's post offers",
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

    public function index(): \Illuminate\Http\JsonResponse
    {
        $postOffers = PostOffer::where('user_id', auth('api')->id())->paginate(10);

        return $this->respondOk($postOffers);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/post-offers",
     *     operationId="Fetch user's post offers for admin",
     *     tags={"Post offers"},
     *     summary="Fetch user's post offers for admin",
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

    public function adminIndex(): \Illuminate\Http\JsonResponse
    {
        $postOffers = PostOffer::paginate(10);
    
        return $this->respondOk($postOffers);
    }


    /**
     * @OA\Post (
     *     path="/api/v1/client/post-offers",
     *     operationId="Create post offer",
     *     tags={"Post offers"},
     *     summary="Create post offer",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Offer is created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Offer not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreatePostOfferVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreatePostOfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(CreatePostOfferRequest $request): \Illuminate\Http\JsonResponse
    {
        $portOffer = PostOffer::create($request->validated() + [
            'user_id' => auth('api')->id()
        ]);

	$admins = User::fetchAdmins();

	Notification::send($admins, new PostOfferNotification((object) $portOffer));

        return $this->respondCreated(['id' => $portOffer->id]);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/post-offers/{offer}",
     *     operationId="Update post offer",
     *     tags={"Post offers"},
     *     summary="Update post offer",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="offer",
     *         in="path",
     *         description="Offer id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Offer is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Offer not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  title="Status of faq",
     *                  description="Status of faq",
     *                  example="received"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  title="message of faq",
     *                  description="message of faq",
     *                  example="some message"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param PatchUpdatePostOfferRequest $request
     * @param PostOffer $offer
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(PatchUpdatePostOfferRequest $request, PostOffer $offer): \Illuminate\Http\JsonResponse
    {
        $offer->update($request->validated());
        return $this->respondOk(['id' => $offer->id]);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/post-offers/{offer}",
     *     operationId="Delete post offer",
     *     tags={"Post offers"},
     *     summary="Delete post offer",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="offer",
     *         in="path",
     *         description="Post offer id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Post offer is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Faq not found with provided id"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param PostOffer $offer
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(PostOffer $offer): \Illuminate\Http\JsonResponse
    {
        $offer->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/post-offers",
     *     operationId="Delete some post offer items",
     *     tags={"Post offers"},
     *     summary="Delete some post offer items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Post items were deleted successfully"
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
        PostOffer::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
