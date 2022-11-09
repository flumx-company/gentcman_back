<?php

/**
 * @OA\Schema(
 *      title="Create product status example",
 *      description="Create product status body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class CreateProductStatusVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new product status",
     *      example="sold"
     * )
     *
     * @var string
     */
    public $name;
}
