<?php

/**
 * @OA\Schema(
 *      title="Update item in bucket request",
 *      description="Update item in request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class UpdateItemInBucketVirtualBody
{
    /**
     * @OA\Property(
     *      title="qty",
     *      description="new qty",
     *      example=2
     * )
     *
     * @var integer
     */
    public $qty;
}
