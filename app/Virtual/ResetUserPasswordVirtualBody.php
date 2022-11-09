<?php

/**
 * @OA\Schema(
 *      title="Reset user password example",
 *      description="Update user password body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class ResetUserPasswordVirtualBody
{
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

    /**
     * @OA\Property(
     *      title="confirm_password",
     *      description="Confirm password of the user",
     *      example="somepassword"
     * )
     *
     * @var string
     */
    public $confirm_password;

    /**
     * @OA\Property(
     *      title="hash",
     *      description="hash code goes here",
     *      example="some hash"
     * )
     *
     * @var string
     */
    public $hash;
}
