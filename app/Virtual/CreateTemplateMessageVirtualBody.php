<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create template message example",
 *      description="Create template message body data",
 *      type="object",
 *      required={"type", "items"}
 * )
 */

class CreateTemplateMessageVirtualBody
{
    /**
     * @OA\Property(
     *      title="type",
     *      description="type of template message",
     *      example="type"
     * )
     *
     * @var string
     */

    public $type;

    /**
     * @OA\Property(
     *    property="items",
     *    title="items",
     *    type="array",
     *    example={{ "name": "received", "message": "message" }},
     *    @OA\Items(
     *         type="object"
     *    ),
     * )
     * @var array
     */

    public $items;
}
