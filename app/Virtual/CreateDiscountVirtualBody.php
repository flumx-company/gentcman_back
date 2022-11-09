<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create discount example",
 *      description="Create discount body data",
 *      type="object",
 *      required={"amount"}
 * )
 */

class CreateDiscountVirtualBody
{
    /**
     * @OA\Property(
     *      title="amount",
     *      description="Amount of percentage for discount",
     *      example="10"
     * )
     *
     * @var integer
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="expires_at",
     *      description="Expires at date",
     *      example="2021-05-25T00:00:00.000000Z"
     * )
     *
     * @var string
     */
    public $expires_at;
}
