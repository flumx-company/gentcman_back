<?php

/**
 * @OA\Schema(
 *      title="Send recovery code request",
 *      description="Send recovery code request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class SendRecoveryCodeVirtualBody
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="Email of the user",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $email;
}
