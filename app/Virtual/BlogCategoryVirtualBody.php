<?php

/**
 * @OA\Schema(
 *      title="Blog category store example",
 *      description="Blog category body data",
 *      type="object",
 *      required={"theme"}
 * )
 */

class BlogCategoryVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Blog category's name",
     *      example="some name"
     * )
     *
     * @var string
     */
    public $name;
}
