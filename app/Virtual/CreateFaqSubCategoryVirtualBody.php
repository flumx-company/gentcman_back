<?php

/**
 * @OA\Schema(
 *      title="Create faq sub category example",
 *      description="Create faq sub category body data",
 *      type="object",
 *      required={"question","answer"}
 * )
 */

class CreateFaqSubCategoryVirtualBody
{
    /**
     * @OA\Property(
     *      title="question",
     *      description="Question of the new faq sub category",
     *      example="some faq sub category"
     * )
     *
     * @var string
     */
    public $question;

    /**
     * @OA\Property(
     *      title="answer",
     *      description="Answer of the new faq sub category",
     *      example="some answer"
     * )
     *
     * @var string
     */
    public $answer;
}
