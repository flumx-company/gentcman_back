<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\TemplateMessage\TemplateMessageRequest;
use Gentcmen\Models\TemplateMessage;

class TemplateMessageController extends ApiController
{
    /**
     * @OA\Get  (
     *     path="/api/v1/admin/template-messages",
     *     operationId="Fetch template messages",
     *     tags={"Template messages"},
     *     summary="Fetch template messages",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Template messages fetched successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(): \Illuminate\Http\JsonResponse
    {
        $templateMessages = TemplateMessage::get();

        return $this->respondOk($templateMessages);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/admin/template-messages",
     *     operationId="Create template message",
     *     tags={"Template messages"},
     *     summary="Create template message",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Template created successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Template not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTemplateMessageVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param TemplateMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(TemplateMessageRequest $request): \Illuminate\Http\JsonResponse
    {
        $templateMessage = TemplateMessage::create($request->validated());

        return $this->respondCreated(['id' => $templateMessage->id]);
    }

    /**
     * @OA\Put (
     *     path="/api/v1/admin/template-messages/{template}",
     *     operationId="Update template message by id",
     *     tags={"Template messages"},
     *     summary="Update template message by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="template",
     *          description="Template id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Template updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Template not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTemplateMessageVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param TemplateMessageRequest $request
     * @param TemplateMessage $template
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(TemplateMessageRequest $request, TemplateMessage $template): \Illuminate\Http\JsonResponse
    {
        $template->update($request->validated());

        return $this->respondOk(['id' => $template->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/template-messages/{template}",
     *     operationId="Delete template message by id",
     *     tags={"Template messages"},
     *     summary="Delete template message by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="template",
     *          description="Template id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Template deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Template not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param TemplateMessage $template
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(TemplateMessage $template): \Illuminate\Http\JsonResponse
    {
        $template->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/template-messages",
     *     operationId="Delete template message items",
     *     tags={"Template messages"},
     *     summary="Delete template message items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Templates deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DestroySomeItemsVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DestroyItemsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroySomeItems(DestroyItemsRequest $request): \Illuminate\Http\JsonResponse
    {
        TemplateMessage::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
