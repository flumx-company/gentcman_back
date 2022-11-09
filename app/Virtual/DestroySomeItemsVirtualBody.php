<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Destroy some items example",
 *      description="Destroy some items body data",
 *      type="object",
 *      required={"ids"}
 * )
 */

class DestroySomeItemsVirtualBody
{
   /**
    *  @OA\Property(
    *          title="ids",
    *          property="ids",
    *          description="Identifiers that should be deleted",
    *          type="array",
    *          example={1,2,3},
    *          @OA\Items(type="integer")
    *      )
    *  )
    */

    public $ids;
}
