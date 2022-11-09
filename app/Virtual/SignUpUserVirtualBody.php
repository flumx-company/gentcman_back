<?php

/**
 * @OA\Schema(
 *      title="Store User request",
 *      description="Store User request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class SignUpUserVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new user",
     *      example="test_user"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the new user",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="password",
     *      description="Password of the new user",
     *      example="somepassword"
     * )
     *
     * @var string
     */
    public $password;

}
