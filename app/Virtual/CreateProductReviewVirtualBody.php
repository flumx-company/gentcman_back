<?php
/**
 * @OA\Schema(
 *      title="Create product review example",
 *      description="Create product review body data",
 *      type="object",
 *      required={"review"}
 * )
 */

class CreateProductReviewVirtualBody
{
    /**
     * @OA\Property(
     *      title="review",
     *      description="Review of the new product review",
     *      example="some feedback goes here"
     * )
     *
     * @var string
     */
    public $review;

    /**
     * @OA\Property(
     *      title="rating",
     *      description="Reting of the new product review",
     *      example="5"
     * )
     *
     * @var integer
     */
    public $rating;

    /**
     * @OA\Property(
     *    property="image[]",
     *    type="array",
     *    @OA\Items(
     *         type="string",
     *         format="binary",
     *    )
     * )
     * @var string
     */

    public $image;
}
