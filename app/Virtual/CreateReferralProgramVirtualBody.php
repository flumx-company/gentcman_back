<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create referral program example",
 *      description="Create referral program body data",
 *      type="object",
 *      required={"name", "uri"}
 * )
 */

class CreateReferralProgramVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the referral program",
     *      example="Sign-up Bonus"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="uri",
     *      description="Uri of the referral program",
     *      example="api/v1/client/sign-up"
     * )
     *
     * @var string
     */
    public $uri;

    /**
     * @OA\Property(
     *      title="lifetime_minutes",
     *      description="Lifetime minutes of the referral program",
     *      example=200
     * )
     *
     * @var integer
     */

    public $lifetime_minutes;

    /**
     * @OA\Property(
     *      title="reward",
     *      description="Reward of program",
     *      example=450
     * )
     *
     * @var integer
     */

    public $reward;
}
