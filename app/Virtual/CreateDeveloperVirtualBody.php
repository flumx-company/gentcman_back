<?php


namespace Gentcmen\Virtual;

/**
 * @OA\Schema(
 *      title="Create faq category example",
 *      description="Create faq category body data",
 *      type="object",
 *      required={"position","first_name","last_name"}
 * )
 */

class CreateDeveloperVirtualBody
{
    /**
     * @OA\Property(
     *      title="position",
     *      description="Position of developer",
     *      example="developer"
     * )
     *
     * @var string
     */
    public $position;

    /**
     * @OA\Property(
     *      title="first_name",
     *      description="First name of developer",
     *      example="user's first name"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="last_name",
     *      description="Last name of developer",
     *      example="user's last name"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="information",
     *      type="object",
     *      description="information of developer",
     *      example={
     *          "contact_field": "test@gmail.com",
     *      }
     * )
     *
     * @var array
     */
    public $information;

    /**
     * @OA\Property(
     *    property="image_link",
     *    type="string"
     * )
     * @var string
     */

    public $image_link;
}
