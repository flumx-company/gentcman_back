<?php

/**
 * @OA\Schema(
 *      title="Create product category example",
 *      description="Create product category body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class CreateProductCategoryVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new product category",
     *      example="belts"
     * )
     *
     * @var string
     */
    public $name;
}
