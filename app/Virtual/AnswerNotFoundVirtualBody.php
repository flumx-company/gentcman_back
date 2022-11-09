<?php

/**
 * @OA\Schema(
 *      title="Answer not found example",
 *      description="Answer not found body data",
 *      type="object",
 *      required={"theme"}
 * )
 */

class AnswerNotFoundVirtualBody
{
    /**
     * @OA\Property(
     *      title="theme",
     *      description="Theme that was not found",
     *      example="some theme"
     * )
     *
     * @var string
     */
    public $theme;

    /**
     * @OA\Property(
     *      title="message",
     *      description="Message of faq",
     *      example="some message"
     * )
     *
     * @var string
     */
    public $message;

    /**
     * @OA\Property(
     *      title="content",
     *      description="Content of the problem",
     *      example="some content goes here"
     * )
     *
     * @var string
     */
    public $content;

    /**
     * @OA\Property(
     *      title="email",
     *      description="Client's email",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Client's name",
     *      example="some name"
     * )
     *
     * @var string
     */
    public $name;
}
