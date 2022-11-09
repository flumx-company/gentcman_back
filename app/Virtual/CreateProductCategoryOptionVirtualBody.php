<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create product review example",
 *      description="Create product review body data",
 *      type="object",
 *      required={"option_name"}
 * )
 */

class CreateProductCategoryOptionVirtualBody
{
    /**
     * @OA\Property(
     *      title="option_name",
     *      description="Option name",
     *      example="some option name"
     * )
     *
     * @var string
     */
    public $option_name;

    /**
     * @OA\Property(
     *      property="option_values",
     *      type="array",
     *      title="option_values",
     *      description="Option values",
     *      example={
     *          {
     *              "value": 20
     *          }
     *     },
     *     @OA\Items(
     *         type="integer"
     *     )
     * )
     *
     * @var array
     */
    public $option_values;
}
