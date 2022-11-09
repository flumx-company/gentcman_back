<?php

/**
 * @OA\Schema(
 *      title="Create faq category example",
 *      description="Create faq category body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class CreateFaqCategoryVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new faq category",
     *      example="some faq category"
     * )
     *
     * @var string
     */
    public $name;
}
