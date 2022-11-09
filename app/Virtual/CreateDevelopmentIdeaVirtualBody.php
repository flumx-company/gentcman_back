<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create development idea example",
 *      description="Create development idea body data",
 *      type="object",
 *      required={"user_name", "email", "idea"}
 * )
 */

class CreateDevelopmentIdeaVirtualBody
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
     *      title="idea",
     *      description="idea content",
     *      example="idea content goes here"
     * )
     *
     * @var string
     */

    public string $idea;
}
