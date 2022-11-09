<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Referral program step example",
 *      description="Referral program step body data",
 *      type="object",
 *      required={"name", "goal", "reward"}
 * )
 */

class CreateReferralProgramStepVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="name of the program step",
     *      example="some program step name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="goal",
     *      description="goal of the program step",
     *      example=2
     * )
     *
     * @var integer
     */
    public $goal;

    /**
     * @OA\Property(
     *      title="reward",
     *      description="reward of the step",
     *      example=400
     * )
     *
     * @var integer
     */
    public $reward;
}
