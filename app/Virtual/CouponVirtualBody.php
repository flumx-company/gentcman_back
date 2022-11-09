<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create coupon example",
 *      description="Create coupon body data",
 *      type="object",
 *      required={"name","cost","discount"}
 * )
 */

class CouponVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the coupon",
     *      example="some name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="cost",
     *      description="Cost of the coupon",
     *      example=450
     * )
     *
     * @var integer
     */
    public $cost;

    /**
     * @OA\Property(
     *      title="discount",
     *      description="discount of the coupon",
     *      example=20
     * )
     *
     * @var integer
     */
    public $discount;

    /**
     * @OA\Property(
     *      title="expires_at",
     *      description="expires of the coupon",
     * )
     *
     * @var date
     */
    public $expires_at;

    /**
     * @OA\Property(
     *      title="entity_type",
     *      description="entity type",
     *      example="Product|ProductCategory"
     * )
     *
     * @var string
     */
    public $entity_type;

    /**
     * @OA\Property(
     *      title="entity_id",
     *      description="entity id",
     *      example=1
     * )
     *
     * @var integer
     */
    public $entity_id;
}
