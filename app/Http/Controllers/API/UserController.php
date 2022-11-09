<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\User\UpdateUserRequest;
use Gentcmen\Http\Requests\ViewHistory\SaveViewHistory;
use Gentcmen\Http\Services\ViewService;
use Gentcmen\Models\Coupon;
use Gentcmen\Models\CouponUser;
use Gentcmen\Models\Product;
use Gentcmen\Models\User;
use Illuminate\Http\Request;

use Cookie;

class UserController extends ApiController
{
    protected $viewService;

    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    public function fetchUserProductsWithReviews(Request $request): \Illuminate\Http\JsonResponse
    {
	$sortBy = $request->query('sort_by') == 'asc'? 'asc': 'desc';

        $userProductsWithReviews = Product::select('id', 'name', 'banner_image', 'created_at', 'description')->with(['reviews' => function ($query) use ($sortBy) {
                $query->where('user_id', auth()->id())->orderBy('created_at',$sortBy);
            }])
            ->whereHas('reviews', function ($query) {
                $query->where('user_id', auth()->id());
//->orderBy('created_at','desc');
            })
            ->paginate(10);

        return $this->respondOk($userProductsWithReviews);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/user/coupons",
     *     operationId="Fetch user coupons",
     *     tags={"User"},
     *     summary="Fetch user coupons",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="User coupons fetched successfully"
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

    public function fetchUserCoupons(): \Illuminate\Http\JsonResponse
    {
        $userCoupons = User::with('coupons')
            ->where('id', auth()->id())
            ->get()
            ->pluck('coupons');

        return $this->respondOk($userCoupons);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/user",
     *     operationId="Fetch user data",
     *     tags={"User"},
     *     summary="Fetch user data",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="User data fetched successfully"
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
        $user = User::with(
                //'orders',
                'roles',
                'bonusPoints',
                'referred_by',
                'referralLinks.relationships.user',
                'referralLinks.program.programSteps.userStep',
            )
            ->find(auth()->id());

        return $this->respondOk($user);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/user",
     *     operationId="Upload avatar",
     *     tags={"User"},
     *     summary="Upload avatar",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="User avatar uploaded successfully"
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
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                  property="avatar_image",
     *                  type="array",
     *                  @OA\Items(
     *                       type="string",
     *                       format="binary",
     *                  ),
     *               ),
     *           ),
     *       )
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $avatarPath = $request->file('avatar_image');
        $avatarName = time() . '.' . $avatarPath->getClientOriginalExtension();

        $path = $request->file('avatar_image')->storeAs('uploads/avatar/'.auth()->id(), $avatarName, 'public');

        $avatarImagePath = '/storage/'.$path;
        auth()->user()->update([
            'avatar' => $avatarImagePath
        ]);

        return $this->respondOk($avatarImagePath);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/user/remove-avatar",
     *     operationId="Delete avatar",
     *     tags={"User"},
     *     summary="Delete avatar",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="User avatar deleted successfully"
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

    public function deleteAvatar(): \Illuminate\Http\JsonResponse
    {
        $filename = explode('/',  auth()->user()->avatar);
        unlink(storage_path('app/public/'.implode('/', array_slice($filename, 2))));

        auth()->user()->update([
            'avatar' => ''
        ]);

        return $this->respondNoContent();
    }


    /**
     * @OA\Put(
     *     path="/api/v1/client/user",
     *     operationId="Update user",
     *     tags={"User"},
     *     summary="Update user",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="User is updated successfully"
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
     *         @OA\JsonContent(
     *          ref="#/components/schemas/UpdateUserVirtualBody"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        User::find(auth()->id())->update([
            'name' => $request->name,
            'surname' => $request->surname,
            //'avatar' => $request->avatar,
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,
            //'state' => $request->state,
            'house' => $request->house,
            'apartment' => $request->apartment,
            'street' => $request->street,
            'email' => $request->email ?:  auth()->user()->email
        ]);

        return $this->respondOk();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/client/user/views",
     *     operationId="Fetch user view history",
     *     tags={"User"},
     *     summary="Fetch user view history",
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
     *          name="groupByDate",
     *          description="order by",
     *          required=false,
     *          in="query",
     *          example="1",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="User's view history data fetched successfully"
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

    public  function  viewHistory(Request $request): \Illuminate\Http\JsonResponse
    {
        $options = [
            'groupByDate' => $request->query('groupByDate'),
	    'text' => $request->query('text'),
	    'cost'=> $request->query('cost'),
            'sort-by'=> $request->query('sort-by'),
            'sort'=> $request->query('sort'),
	    'published'=> $request->query('published')
        ];

        $viewHistory = $this->viewService->getUserViewedEntries($request->query('page'), null, $options);

        return $this->respondOk($viewHistory);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/client/user/views",
     *     operationId="Upload user's view history",
     *     tags={"User"},
     *     summary="Upload user's view history",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="User history uploaded successfully"
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
     *         @OA\JsonContent(
     *              @OA\Property (
     *                  property="product_ids",
     *                  type="array",
     *                  example={10, 20},
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param SaveViewHistory $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function saveViewHistory(SaveViewHistory $request): \Illuminate\Http\JsonResponse
    {
        $products = Product::find($request->product_ids);
	$ids_dates=$request->ids_dates?? array();

        foreach ($products as $product)
        {

	    $finddate_by_id = collect($ids_dates)->filter(function($item) use ($product) {
		return $item["id"] == $product->id;
	    })->first();
            $this->viewService->add($product, $finddate_by_id ? $finddate_by_id['date'] : false);
        }

        return $this->respondCreated();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/user/views/{viewableId}",
     *     operationId="Remove view item",
     *     tags={"User"},
     *     summary="Remove view item",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="viewableId",
     *         in="path",
     *         description="Viewable id item",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Viewable item deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param int $viewableId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeViewHistoryItem(Request $request, int $viewableId): \Illuminate\Http\JsonResponse
    {
        $this->viewService->destroy($viewableId, $request->query('date'));

        return $this->respondNoContent();
    }

    /**
     * Delete all user views from DB
     * @return \Illuminate\Http\JsonResponse
     */

    public function resetAllUserViews(): \Illuminate\Http\JsonResponse
    {
        $this->viewService->resetAllUserViews();

        return $this->respondNoContent();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/user/coupons/{coupon}",
     *     operationId="Buy a coupon for user",
     *     tags={"User"},
     *     summary="Buy a coupon for user",
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
     *         description="Coupon assigned to user successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Coupon not found"
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Coupon $coupon
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function buyCoupon(Request $request, Coupon $coupon): \Illuminate\Http\JsonResponse
    {
        $this->authorize('buy-coupon', $coupon);

        auth()->user()->attachCoupon($coupon, CouponUser::STATUS_BOUGHT);

        auth()->user()->decrementBonusPoints($coupon->cost);

        return $this->respondOk();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/client/user/coupons",
     *     operationId="Remove user coupons",
     *     tags={"User"},
     *     summary="Remove user coupons",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="User coupons removed successfully"
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

    public function destroyUserCoupons(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        auth()->user()->detachCoupon($request->ids, CouponUser::STATUS_DELETED);

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/user/views",
     *     operationId="Remove all user's viewable items",
     *     tags={"User"},
     *     summary="Remove all user's viewable items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Viewable items deleted successfully"
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
        $result = $this->viewService->resetAllUserViews();

        return $this->respondNoContent();
    }
}
