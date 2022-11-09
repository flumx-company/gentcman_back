<?php

/**
 * @OA\Schema(
 *      title="Log in User request",
 *      description="Log in User request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class SignInUserVirtualBody
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the user",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="password",
     *      description="Password of the user",
     *      example="somepassword"
     * )
     *
     * @var string
     */
    public $password;
}
