<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Update Admin request",
 *      description="Update Admin request body data",
 *      type="object",
 *      required={"name", "email", "password"}
 * )
 */

class PatchUpdateAdminVirtualBody
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
