<?php

namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Banner body example",
 *      description="Banner body data",
 *      type="object",
 *      required={
 *          "title"
 *      }
 * )
 */

class BannerVirtualBody
{
    /**
     * @OA\Property(
     *      title="title",
     *      description="Title of banner",
     *      example="some title"
     * )
     *
     * @var string
     */

    public $title;

    /**
     * @OA\Property(
     *      title="title_color",
     *      description="Title color of banner",
     *      example="some title_color"
     * )
     *
     * @var string
     */

    public $title_color;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Description of banner",
     *      example="some description"
     * )
     *
     * @var string
     */

    public $description;

    /**
     * @OA\Property(
     *      title="description_color",
     *      description="Description color of banner",
     *      example="some description_color"
     * )
     *
     * @var string
     */

    public $description_color;

    /**
     * @OA\Property(
     *      title="button_text",
     *      description="Button text of banner",
     *      example="some button_text"
     * )
     *
     * @var string
     */

    public $button_text;

    /**
     * @OA\Property(
     *      title="button_link",
     *      description="Button link of banner",
     *      example="some button_link"
     * )
     *
     * @var string
     */

    public $button_link;

    /**
     * @OA\Property(
     *      title="image_link",
     *      description="Image link of banner",
     *      example="some image_link"
     * )
     *
     * @var string
     */

    public $image_link;

    /**
     * @OA\Property(
     *      title="image_position",
     *      description="Image position of banner",
     *      example="some image_position"
     * )
     *
     * @var string
     */

    public $image_position;

    /**
     * @OA\Property(
     *      title="text_info",
     *      description="Text info of banner",
     *      example="some text_info"
     * )
     *
     * @var string
     */

    public $text_info;

    /**
     * @OA\Property(
     *      title="block_background",
     *      description="Block bacground of banner",
     *      example="some block_background"
     * )
     *
     * @var string
     */

    public $block_background;


    /**
     * @OA\Property(
     *      title="button_color",
     *      description="Button color of banner",
     *      example="some button_color"
     * )
     *
     * @var string
     */

    public $button_color;

    /**
     * @OA\Property(
     *      title="button_border",
     *      description="Button border of banner",
     *      example="some button_border"
     * )
     *
     * @var string
     */

    public $button_border;

    /**
     * @OA\Property(
     *      title="button_background",
     *      description="Button background of banner",
     *      example="some button_background"
     * )
     *
     * @var string
     */

    public $button_background;

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

    /**
     * @OA\Property(
     *      title="template",
     *      description="template of banner",
     *      example="something"
     * )
     *
     * @var string
     */

    public $template;
}
