<?php

/**
 * @OA\Schema(
 *      title="Report a problem example",
 *      description="Report a problem body data",
 *      type="object",
 *      required={"theme", "content", "message", "user_email", "user_name"}
 * )
 */

class ReportProblemVirtualBody
{
    /**
     * @OA\Property(
     *      title="theme",
     *      description="theme of report",
     *      example="theme"
     * )
     *
     * @var string
     */
    public $theme;

    /**
     * @OA\Property(
     *      title="content",
     *      description="content of report",
     *      example="content"
     * )
     *
     * @var string
     */
    public $content;

    /**
     * @OA\Property(
     *      title="message",
     *      description="message of report",
     *      example="message"
     * )
     *
     * @var string
     */
    public $message;

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
