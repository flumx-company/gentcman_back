<?php

/**
 * @OA\Schema(
 *      title="Store favorite items request",
 *      description="Store favorite items request body data",
 *      type="object",
 *      required={"favorite_items"}
 * )
 */

class StoreFavoriteItemsVirtualBody
{
    /**
     * @OA\Property(
     *      property="favorite_items",
     *      type="array",
     *      @OA\Items(
     *          @OA\Property(
     *              property="product_id",
     *              title="Product id",
     *              description="product id",
     *              type="integer",
     *              example=1
     *          )
     *      )
     * )
     *
     * @var array
     */
    public $favorite_items;
}
