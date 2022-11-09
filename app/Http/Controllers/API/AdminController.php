<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\Admin\CreateAdminRequest;
use Gentcmen\Http\Requests\Admin\PatchUpdateAdminRequest;
use Gentcmen\Http\Requests\General\ManageBonusPointsRequest;
use Gentcmen\Http\Requests\ManageCoupon\AssignCouponRequest;
use Gentcmen\Http\Requests\ManageCoupon\DeleteCouponRequest;
use Gentcmen\Models\Coupon;
use Gentcmen\Models\CouponUser;
use Gentcmen\Models\Role;
use Gentcmen\Http\Controllers\API\UserQueryFilters\UserFilter;
use Gentcmen\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends ApiController
{
    public function createFilter(Request $request): \Illuminate\Http\JsonResponse
    {
        $filterName = $request->filter_name;
        $filterType = $request->filter_type;
        $namespace = '';
        $filterFolder = '';

        if ($filterType === 'product')
        {
            $namespace = "<?php \n\nnamespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;\nuse Illuminate\Database\Eloquent\Builder;\n\n";
            $filterFolder = "ProductQueryFilters";
        }

        if ($filterFolder)
        {
            $path = base_path()."\app\Http\Controllers\API\\$filterFolder\Filters\\"."$filterName.php";
            $file = fopen($path, "w");
            $fileContent = $namespace . $request->content;
            fwrite($file, $fileContent);

            return $this->respondOk();
        }

        return $this->respondNoContent();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users",
     *     operationId="Fetch users by query params",
     *     tags={"Admin"},
     *     summary="Fetch users by query params",
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
     *        name="is_admin",
     *        description="Fetch admins",
     *        in="query",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Parameter(
     *        name="user_city",
     *        description="User's city",
     *        in="query",
     *        example="some user name",
     *        @OA\Schema(
     *            type="string"
     *        )
     *     ),
     *      @OA\Parameter(
     *          name="user_name",
     *          description="User's name",
     *          in="query",
     *          example="some user name",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Users fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function querySearch(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = UserFilter::apply($request);
        return $this->respondOk($users);
    }

    public function getAdmin ()
    {
       return $this->respondOk(\auth()->user());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users/{user}",
     *     operationId="Fetch user by id",
     *     tags={"Admin"},
     *     summary="Fetch user by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */

    public function getById(User $user): \Illuminate\Http\JsonResponse
    {
        $user->load('roles', 'bonusPoints', 'coupons', 'referred_by');

        return $this->respondOk($user);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users/{user}/coupons",
     *     operationId="Fetch user's coupons",
     *     tags={"Admin"},
     *     summary="Fetch user's coupons",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User's id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User's coupons fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function getUserCoupons(User $user): \Illuminate\Http\JsonResponse
    {
        $userCoupons = CouponUser::with('coupon')->where('user_id', $user->id)->get();//->paginate(10);

        return $this->respondOk($userCoupons);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/users/create-admin",
     *     operationId="Create admin",
     *     tags={"Admin"},
     *     summary="Create a new admin",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Admin created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *    @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateAdminVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param CreateAdminRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function createAdmin(CreateAdminRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::create($request->validated());
        $user->roles()->attach(Role::IS_ADMIN);

        return $this->respondCreated(['id' => $user->id]);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/{admin}",
     *     operationId="Update admin by id",
     *     tags={"Admin"},
     *     summary="Update admin by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="admin",
     *         in="path",
     *         description="Admin id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Admin is updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Admin not found with provided id"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PatchUpdateAdminVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(PatchUpdateAdminRequest $request, User $admin): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update-admin', $admin);

        $bodyRequest = $request->validated();

        if (array_key_exists('password', $bodyRequest)) {
            $bodyRequest = array_merge($bodyRequest, ['password' => Hash::make($bodyRequest['password'])]);
        }

	if (array_key_exists('email', $bodyRequest) && $bodyRequest['email'] !== $admin->email) {
            $bodyRequest = array_merge($bodyRequest, ['email' => $bodyRequest['email']]);
        }

        $admin->update($bodyRequest);

        return $this->respondOk();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/users/{user}",
     *     operationId="Remove user",
     *     tags={"Admin"},
     *     summary="Remove user",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="User was deleted successfully"
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
     */

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        $user->delete();
        return $this->respondNoContent();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/users/{user}/manage-bonus-points",
     *     operationId="manage bonus points",
     *     tags={"Admin"},
     *     summary="Manage bonus points",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Bonus points updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          @OA\Property(
     *              property="amount",
     *              title="Amount",
     *              description="Status of order",
     *              example=200
     *          ),
     *          @OA\Property(
     *              property="operation",
     *              title="Operation",
     *              description="increment or decrement",
     *              example="increment"
     *          )
     *        )
     *    )
     * )
     *
     * Display a listing of the resource.
     *
     * @param ManageBonusPointsRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */

    public function manageBonusPoints(ManageBonusPointsRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        $operation = $request->operation . 'BonusPoints';

        $user->$operation($request->amount);

        return $this->respondOk();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/users/{user}/manage-coupons",
     *     operationId="manage coupons",
     *     tags={"Admin"},
     *     summary="Manage coupons",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Coupons for user updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Entity not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DestroySomeItemsVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     */

    public function manageCoupons(AssignCouponRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        foreach ($request->ids as $id)
        {
            $coupon = Coupon::find($id);

            if ($user->coupons()->get()->contains($coupon->id)) continue;

            $user->attachCoupon($coupon, CouponUser::STATUS_ACCRUED);

            //$user->decrementBonusPoints($coupon->cost);
        }

        return $this->respondOk();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/users/{user}/manage-coupons",
     *     operationId="Remove user's coupon",
     *     tags={"Admin"},
     *     summary="Remove user's coupon",
     *     security={ {"bearer": {}} },
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Coupons for user updated successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  title="ids",
     *                  property="ids",
     *                  description="Identifiers that should be deleted",
     *                  type="array",
     *                  example={1,2,3},
     *                  @OA\Items(type="integer")
     *              ),
     *              @OA\Property(
     *                  title="forceDelete",
     *                  property="forceDelete",
     *                  description="Identifiers that should be deleted anyway",
     *                  type="array",
     *                  example={1,2,3},
     *                  @OA\Items(type="integer")
     *                  )
     *              )
     *          )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     */

    public function removeUserCoupon(DeleteCouponRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        if ($request->ids)
        {
            $user->detachCoupon($request->ids, CouponUser::STATUS_DELETED);
        }

        if ($request->forceDelete)
        {
            $user->detachCoupon($request->forceDelete, '', true);
        }

        return $this->respondNoContent();
    }
}
