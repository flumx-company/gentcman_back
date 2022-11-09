<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Events\UserReferredEvent;
use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Models\BonusPoint;
use Gentcmen\Models\ReferralLink;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Cookie;

use Hash;

use Gentcmen\Http\Requests\Auth\RecoverUserPasswordRequest;
use Gentcmen\Http\Requests\Auth\SendRecoveryCodeRequest;
use Gentcmen\Http\Requests\Auth\SignInUserRequest;
use Gentcmen\Http\Requests\Auth\SendConfirmEmailRequest;
use Gentcmen\Http\Requests\Auth\SignUpUserRequest;
use Gentcmen\Jobs\SendRecoverCodeJob;
use Gentcmen\Jobs\SendConfirmEmailJob;
use Gentcmen\Models\OauthAccessToken;
use Gentcmen\Models\User;
use Gentcmen\Models\Role;
use Gentcmen\Models\PasswordResets;

use Carbon\Carbon;


class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/client/sign-up",
     *     operationId="Sign Up",
     *     tags={"Auth"},
     *     summary="Sign up in the system",
     *     @OA\Parameter(
     *         name="ref",
     *         in="query",
     *         description="Ref code",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Registered successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/SignUpUserVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function signUp(SignUpUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $password = Hash::make($request->password);

        $cookie = request()->cookie('ref') ?? $request->header('referral_id');
        $referred_by = $cookie ? ReferralLink::find($cookie) : null;

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'referred_by' => $referred_by->user_id ?? null
            ]);

            $user->initReferrals();

            BonusPoint::create([
               'points' => 0,
               'user_id' => $user->id
            ]);

            event(new UserReferredEvent($cookie, $user));

        } catch (\Throwable $th) {
            return $this->respondBadRequest($th);
        }

        $user->roles()->attach(Role::IS_USER);

        return $this->respondCreated(['user_id' => $user->id], "Registered successfully");
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/sign-in",
     *     operationId="Sign In",
     *     tags={"Auth"},
     *     summary="Sign in in the system",
     *     @OA\Response(
     *         response="200",
     *         description="Logged in successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/SignInUserVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */

    public function signIn(SignInUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            $cookie = getCookieDetails(getAccessToken($user));

            return $this->respondOk([
                'logged_in_user_id' => $user->id,
                'token' => getAccessToken($user),
            ])
                ->cookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['minutes'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httponly'],
                    $cookie['samesite']
                );
        }

        return $this->respondBadRequest('Provide correct credentials');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/send-recover-code",
     *     operationId="Recover code",
     *     tags={"Auth"},
     *     summary="Send recovery code on the email to reset password",
     *     @OA\Response(
     *         response="200",
     *         description="Email was sent"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SendRecoveryCodeVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function sendRecoverCode(SendRecoveryCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('email',  $request->email)->first();

        if (!$user) {
            return $this->respondNotFound('User not found for provided email');
        }

        $hash = base64_encode(base64_encode($user->id.$request->email));

        PasswordResets::create([
            'email' => $request->email,
            'token' => $hash,
            'expires_at' => Carbon::now()->addHours(),
        ]);

        $host_url = $request->backend_url ?: env("APP_FRONTEND_URL");
        $link = $host_url . "?modalType=reset-password&hash=" . $hash;

        $details = [
            'url' => $link,
            'name' => $user->name
        ];

        SendRecoverCodeJob::dispatch($user->email, $details);

        return $this->respondOk();
    }

    public function sendConfirmEmail(SendConfirmEmailRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return $this->respondNotFound('User not found for provided email');
        }

        $hash = base64_encode(base64_encode($user->id));

        $host_url = $request->backend_url ?: env("APP_FRONTEND_URL");
        $link = $host_url . "?hash=" . $hash . "&redirectUri=".$request->redirect_url;

        $details = [
            'url' => $link,
            'name' => $user->name
        ];

        SendConfirmEmailJob::dispatch($user->email, $details);

        return $this->respondOk();
    }

    public function confirmEmail(Request $request)
    {

        $hash = base64_decode(base64_decode($request->query('hash')));
        $host_url = $request->query('redirectUri');

        $user = User::find($hash);

        if (!$user) {
            return redirect($host_url.'?type=confirm_email&error=user_not_found');
        }
        $user->update(['email_verified_at' => true]);
        return redirect($host_url.'?type=confirm_email&success=email_confirmed');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/client/reset-password",
     *     operationId="Recover password",
     *     tags={"Auth"},
     *     summary="Recover user's password",
     *     @OA\Response(
     *         response="200",
     *         description="Password was updated"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User no found for this email, Please use another email"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResetUserPasswordVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(RecoverUserPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $passwordResets = PasswordResets::where('token', $request->hash)->first();
        $user = User::where('email', $passwordResets->email)->first();

        if (!$passwordResets || !$user) {
            return $this->respondNotFound('User not found for provided email');
        }

        if ($passwordResets->expires_at->lessThan(Carbon::now())) {
            return $this->respondBadRequest('Your link is expired.');
        }

         User::where('email', $passwordResets->email)
                ->update([
                    'password' => Hash::make($request->password)
                ]);

        $passwordResets->delete();

        return $this->respondOk();
    }


    /**
     * @OA\Get(
     *     path="/api/v1/client/check-exist-email",
     *     operationId="Check if user exists by email",
     *     tags={"Auth"},
     *     summary="Check if user exists by providing email address",
     *     @OA\Response(
     *         response="200",
     *         description="Email don`t used in our system"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Email already exists. Please use another email"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The user email",
     *         required=false,
     *         example="test@gmail.com",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkExistEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        if (User::where('email', $request->query('email'))->exists())
        {
            return $this->respondBadRequest('Email already exists');
        }

        return $this->respondOk();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/client/logout",
     *     operationId="Make a log out",
     *     tags={"Auth"},
     *     summary="Make a log out",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="User logged out successfully."
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Email already exists. Please use another email"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        OauthAccessToken::where('user_id', Auth::id())->delete();

        $request->user()->token()->revoke();
        $cookie = Cookie::forget('_token');

        return $this->respondOk()->withCookie($cookie);
    }
}
