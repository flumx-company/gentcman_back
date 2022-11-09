<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Http\Requests\IdeaImprovement\IdeaImprovementRequest;
use Gentcmen\Http\Requests\IdeaImprovement\PatchUpdateIdeaImprovementRequest;
use Gentcmen\Models\IdeaImprovement;
use Gentcmen\Models\User;
use Gentcmen\Notifications\ImprovementIdeaNotification;
use Illuminate\Support\Facades\Notification;

class ImprovementIdeaController extends ApiController
{

    /**
     * @OA\Get  (
     *     path="/api/v1/client/idea-improvements",
     *     operationId="Fetch idea improvements for client",
     *     tags={"Idea improvements"},
     *     summary="Fetch idea improvements for client - auth user",
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
        $ideaImprovements = IdeaImprovement::where('user_id', auth('api')->id())->paginate(10);

        return $this->respondOk($ideaImprovements);
    }


    /**
     * @OA\Get  (
     *     path="/api/v1/admin/idea-improvements",
     *     operationId="Fetch idea improvements for admin",
     *     tags={"Idea improvements"},
     *     summary="Fetch idea improvements for admin",
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

    public function adminIndex(): \Illuminate\Http\JsonResponse
    {
        $ideaImprovements = IdeaImprovement::paginate(10);

        return $this->respondOk($ideaImprovements);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/client/idea-improvements",
     *     operationId="Create idea-improvement",
     *     tags={"Idea improvements"},
     *     summary="Create idea-improvement",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Idea improvement created successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Idea improvement not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateIdeaImprovementVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param IdeaImprovementRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(IdeaImprovementRequest $request): \Illuminate\Http\JsonResponse
    {
        $ideaImprovement = IdeaImprovement::create($request->validated() + [
            'user_id' => auth('api')->id()
        ]);

        Notification::send(User::fetchAdmins(), new ImprovementIdeaNotification($request->validated()));

        return $this->respondCreated(['id' => $ideaImprovement->id]);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/idea-improvements/{improvement}",
     *     operationId="Patch update idea-improvement",
     *     tags={"Idea improvements"},
     *     summary="Patch update idea-improvement",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="improvement",
     *          description="Improvement id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Idea improvement updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Idea improvement not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  title="Status of faq",
     *                  description="Status of faq",
     *                  example="received"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  title="message of faq",
     *                  description="message of faq",
     *                  example="some message"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param IdeaImprovementRequest $request
     * @param IdeaImprovement $improvement
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(PatchUpdateIdeaImprovementRequest $request, IdeaImprovement $improvement): \Illuminate\Http\JsonResponse
    {
        $improvement->update($request->validated());

        return $this->respondOk(['id' => $improvement->id]);
    }

    /**
     * @OA\Put (
     *     path="/api/v1/client/idea-improvements/{improvement}",
     *     operationId="Update idea-improvement",
     *     tags={"Idea improvements"},
     *     summary="Update idea-improvement",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="improvement",
     *          description="Improvement id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Idea improvement updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Idea improvement not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateIdeaImprovementVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param IdeaImprovementRequest $request
     * @param IdeaImprovement $improvement
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(IdeaImprovementRequest $request, IdeaImprovement $improvement): \Illuminate\Http\JsonResponse
    {
        $improvement->update($request->validated());

        return $this->respondOk(['id' => $improvement->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/idea-improvements/{improvement}",
     *     operationId="Delete idea improvement by id",
     *     tags={"Idea improvements"},
     *     summary="Delete idea improvement by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="improvement",
     *          description="Improvement id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Improvement deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Improvement not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param IdeaImprovement $improvement
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(IdeaImprovement $improvement): \Illuminate\Http\JsonResponse
    {
        $improvement->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/idea-improvements",
     *     operationId="Delete idea improvements items",
     *     tags={"Idea improvements"},
     *     summary="Delete idea improvements items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Idea improvements deleted successfully"
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
        IdeaImprovement::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
