<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Partner request body example",
 *      description="Partner request body data",
 *      type="object",
 *      required={"ids"}
 * )
 */

class PartnerVirtualData
{
    /**
     *  @OA\Property(
     *          title="name",
     *          property="name",
     *          description="name of partner",
     *          type="string",
     *          example="some name"
     *      )
     *  )
     */

    public $name;

    /**
     *  @OA\Property(
     *          title="description",
     *          property="description",
     *          description="description of partner",
     *          type="string",
     *          example="some description"
     *      )
     *  )
     */

    public $description;

    /**
     *  @OA\Property(
     *          title="image_link",
     *          property="image_link",
     *          description="image_link of partner",
     *          type="string",
     *          example="image_link"
     *      )
     *  )
     */

    public $image_link;

    /**
     *  @OA\Property(
     *          title="site_link",
     *          property="site_link",
     *          description="site_link of partner",
     *          type="string",
     *          example="site_link"
     *      )
     *  )
     */

    public $site_link;
}
