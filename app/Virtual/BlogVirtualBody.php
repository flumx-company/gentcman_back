<?php

/**
 * @OA\Schema(
 *      title="Create blog example",
 *      description="Create blog body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class BlogVirtualBody
{
    /**
     * @OA\Property(
     *      title="title",
     *      description="Title of the new blog",
     *      example="some text"
     * )
     *
     * @var string
     */
    public $title;

    /**
     * @OA\Property(
     *      title="short_content",
     *      description="Short content of the new blog",
     *      example="some content"
     * )
     *
     * @var string
     */
    public $short_content;

    /**
     * @OA\Property(
     *      title="content",
     *      description="content of the blog",
     *      type="json",
     *      example={"image_url": "f"}
     * )
     *
     * @var array
     */
    public $content;


    /**
     * @OA\Property(
     *      title="type_id",
     *      description="Type id of the new blog",
     *      example=1
     * )
     *
     * @var integer
     */
    public $type_id;

    /**
     * @OA\Property(
     *      title="category_id",
     *      description="Category id of the new blog",
     *      example=1
     * )
     *
     * @var integer
     */
    public $category_id;

    /**
     * @OA\Property(
     *      title="image_title",
     *      description="Image title",
     *      example="image_title goes here"
     * )
     *
     * @var integer
     */

    public $image_title;

    /**
     * @OA\Property(
     *      title="meta_title",
     *      description="Meta title",
     *      example="meta_title goes here"
     * )
     *
     * @var integer
     */

    public $meta_title;

    /**
     * @OA\Property(
     *      title="meta_description",
     *      description="Meta description",
     *      example="meta_description goes here"
     * )
     *
     * @var integer
     */

    public $meta_description;

    /**
     * @OA\Property(
     *      title="meta_keywords",
     *      description="Meta keywords",
     *      example="meta_keywords goes here"
     * )
     *
     * @var integer
     */

    public $meta_keywords;

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
