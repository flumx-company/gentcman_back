<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Store Admin request",
 *      description="Store Admin request body data",
 *      type="object",
 *      required={"name", "email", "password"}
 * )
 */

class CreateAdminVirtualBody
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
