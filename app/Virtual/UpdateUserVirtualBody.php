<?php

/**
 * @OA\Schema(
 *      title="Update user data request",
 *      description="Update user data request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class UpdateUserVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="User's name",
     *      example="name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="surname",
     *      description="User's surname",
     *      example="surname"
     * )
     *
     * @var string
     */
    public $surname;

    /**
     * @OA\Property(
     *      title="avatar",
     *      description="User's avatar",
     *      example="avatar"
     * )
     *
     * @var string
     */
    public $avatar;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="User's phone",
     *      example="phone"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="country",
     *      description="User's country",
     *      example="country"
     * )
     *
     * @var string
     */
    public $country;

    /**
     * @OA\Property(
     *      title="city",
     *      description="User's city",
     *      example="city"
     * )
     *
     * @var string
     */
    public $city;

    /**
     * @OA\Property(
     *      title="state",
     *      description="User's state",
     *      example="state"
     * )
     *
     * @var string
     */
    public $state;

    /**
     * @OA\Property(
     *      title="apartment",
     *      description="User's apartment",
     *      example="apartment"
     * )
     *
     * @var string
     */
    public $apartment;
}
