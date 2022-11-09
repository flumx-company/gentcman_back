<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create post offer example",
 *      description="Create post offer body data",
 *      type="object",
 *      required={"theme", "text", "user_name", "email"}
 * )
 */

class CreatePostOfferVirtualBody
{
    /**
     * @OA\Property(
     *      title="theme",
     *      description="Theme of post offer",
     *      example="some theme"
     * )
     *
     * @var string
     */

    public $theme;

    /**
     * @OA\Property(
     *      title="text",
     *      description="Text of post offer",
     *      example="some text"
     * )
     *
     * @var string
     */

    public $text;

    /**
     * @OA\Property(
     *      title="user_name",
     *      description="User name of post offer",
     *      example="some name"
     * )
     *
     * @var string
     */

    public $user_name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="User email of post offer",
     *      example="some email"
     * )
     *
     * @var string
     */

    public $email;
}
