<?php

/**
 * @OA\Schema(
 *      title="Store items to bucket request",
 *      description="Store items to bucket request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class StoreItemsToBucketVirtualBody
{
    /**
     * @OA\Property(
     *      title="bucket_items",
     *      description="bucket items",
     *      type="json",
     *      example={{"product_id": 1, "qty": 3}}
     * )
     *
     * @var array
     */
    public $bucket_items;
}
