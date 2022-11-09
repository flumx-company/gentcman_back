<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Models\User;
use Exception;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController
{
    private const GOOGLE_PROVIDER = "google";
    private const FACEBOOK_PROVIDER = "facebook";

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle(): Response
    {
        return $this->getStatelessStateByProvider(self::GOOGLE_PROVIDER)
                    ->redirect();
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook(): Response
    {
        return $this->getStatelessStateByProvider(self::FACEBOOK_PROVIDER)
                    ->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback(): Response|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $user = $this->getStatelessStateByProvider(self::GOOGLE_PROVIDER)
                         ->user();

            $user = User::firstOrCreate(
                [
                    $this->joinAtEndStringId(self::GOOGLE_PROVIDER) => $user->id
                ],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    $this->joinAtEndStringId(self::GOOGLE_PROVIDER) => $user->id,
                    'avatar'=> $user->avatar,
                    'password' => encrypt('')
                ]
            );
            return $this->handleSuccessfulResponse($user, "Social Google Login");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function handleFacebookCallback(): Response|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $user = $this->getStatelessStateByProvider(self::FACEBOOK_PROVIDER)
                         ->user();

            $user = User::firstOrCreate(
                [
                    $this->joinAtEndStringId(self::FACEBOOK_PROVIDER) => $user->id
                ],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    $this->joinAtEndStringId(self::FACEBOOK_PROVIDER) => $user->id,
                    'avatar'=> $user->avatar,
                    'password' => encrypt('')
                ]
            );

            return $this->handleSuccessfulResponse($user, "Social Facebook Login");
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function handleSuccessfulResponse(User $user, string $socialName): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $token = getAccessToken($user, $socialName);
        $cookie = getCookieDetails($token);

        $redirectUrl = env('APP_ENV') == 'local'
                            ? 'http://127.0.0.1:8000'
                            : env('APP_FRONTEND_URL');

        return redirect($redirectUrl)
                    ->withCookie(
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

    private function getStatelessStateByProvider(string $driver) {
        return Socialite::driver($driver)
                            ->stateless();
    }

    /**
     *  Get provided string with '_id' on the end. For example passed 'google' string to function, its gonna return 'google_id'
        @returns string
    */

    private function joinAtEndStringId(string $driver): string
    {
        return $driver .'_id';
    }
}
