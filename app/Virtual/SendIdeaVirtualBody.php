<?php

namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Send idea example",
 *      description="Send idea body data",
 *      type="object",
 *      required={"theme", "content", "user_email", "user_name"}
 * )
 */

class SendIdeaVirtualBody
{
    /**
     * @OA\Property(
     *      title="theme",
     *      description="theme of idea",
     *      example="theme"
     * )
     *
     * @var string
     */
    public $theme;

    /**
     * @OA\Property(
     *      title="content",
     *      description="content of idea",
     *      example="content"
     * )
     *
     * @var string
     */
    public $content;

    /**
     * @OA\Property(
     *      title="user_email",
     *      description="user email",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $user_email;

    /**
     * @OA\Property(
     *      title="user_name",
     *      description="user name",
     *      example="test"
     * )
     *
     * @var string
     */
    public $user_name;
}
