<?php

/**
 * @OA\Schema(
 *      title="Update product example",
 *      description="Update product body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class UpdateProductVirtualBody
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new product",
     *      example="belt"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="cost",
     *      description="Cost of the new product",
     *      example="200"
     * )
     *
     * @var integer
     */
    public $cost;

    /**
     * @OA\Property(
     *      title="amount",
     *      description="Amount of the new product",
     *      example="12"
     * )
     *
     * @var integer
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Description of the new product",
     *      example="some description"
     * )
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="content",
     *      type="json",
     *      description="Content of the new product",
     *      example={
     *          "width": 40,
     *          "color": "blue",
     *          "length": "100",
     *          "gender": "male",
     *          "belt_type": "classic",
     *          "clasp_type": "classic"
     *      }
     * )
     *
     * @var json
     */
    public $content;

    /**
     * @OA\Property(
     *      title="status_ids",
     *      description="Identifiers of statuses for the new product",
     *      type="integer",
     *      example=1,
     * )
     *
     * @var integer
     */
    public $status_id;

    /**
     * @OA\Property(
     *      property="category_ids",
     *      title="category_ids",
     *      description="Identifiers of categories for the new product",
     *      type="array",
     *      example={1},
     *      @OA\Items(
     *          type="integer"
     *      )
     * )
     *
     * @var array
     */
    public $category_ids;

     /**
     * @OA\Property(
     *    property="image[]",
     *    title="image",
     *    type="array",
     *    @OA\Items(
     *         type="string",
     *         format="binary",
     *    ),
     * )
     * @var array
     */

     public $image;

    /**
     * @OA\Property(
     *      title="images_content",
     *      type="json",
     *      description="images_content of the new product",
     *      example={
     *          "": 40,
     *          "color": "blue",
     *          "length": "100",
     *          "gender": "male",
     *          "belt_type": "classic",
     *          "clasp_type": "classic"
     *      }
     * )
     *
     * @var json
     */
    public $images_content;

    /**
     * @OA\Property(
     *    property="meta_title",
     *    title="Meta title",
     *    type="string",
     *    example="meta title"
     * )
     * @var array
     */

    public $meta_title;

    /**
     * @OA\Property(
     *    property="meta_description",
     *    title="Meta description",
     *    type="string",
     *    example="meta description"
     * )
     * @var array
     */

    public $meta_description;

    /**
     * @OA\Property(
     *    property="meta_keywords",
     *    title="Meta keywords",
     *    type="string",
     *    example="meta keywords"
     * )
     * @var array
     */

    public $meta_keywords;

    /**
     * @OA\Property(
     *    property="banner_image",
     *    title="Banner image",
     *    type="string",
     *    example="banner image"
     * )
     * @var string
     */

    public $banner_image;

    /**
     * @OA\Property(
     *      title="published",
     *      description="published or not banner",
     *      example=true
     * )
     *
     * @var boolean
     */

    public $published;
}
