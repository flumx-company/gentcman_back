<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create idea improvement example",
 *      description="Create idea improvement body data",
 *      type="object",
 *      required={"user_name", "email", "improvement"}
 * )
 */

class CreateIdeaImprovementVirtualBody
{
    /**
     * @OA\Property(
     *      title="user_name",
     *      description="user name who create idea improvement",
     *      example="user name"
     * )
     *
     * @var string
     */

    public string $user_name;

    /**
     * @OA\Property(
     *      title="email",
     *      description="user email who create idea improvement",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */

    public string $email;

    /**
     * @OA\Property(
     *      title="improvement",
     *      description="improvement content",
     *      example="improvement content goes here"
     * )
     *
     * @var string
     */

    public string $improvement;

    /**
     * @OA\Property(
     *      title="theme",
     *      description="theme content",
     *      example="theme content goes here"
     * )
     *
     * @var string
     */

    public string $theme;
}
