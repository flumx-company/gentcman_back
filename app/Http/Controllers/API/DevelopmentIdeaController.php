<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\DevelopmentIdea\DevelopmentIdeaRequest;
use Gentcmen\Http\Requests\DevelopmentIdea\PatchUpdateDevelopmentIdeaRequest;
use Gentcmen\Http\Requests\General\DestroyItemsRequest;
use Gentcmen\Models\DevelopmentIdea;
use Gentcmen\Models\User;
use Gentcmen\Notifications\DevelopmentIdeaNotification;
use Illuminate\Support\Facades\Notification;

class DevelopmentIdeaController extends ApiController
{
    /**
     * @OA\Get  (
     *     path="/api/v1/client/development-ideas",
     *     operationId="Fetch development ideas for client - auth user",
     *     tags={"Development ideas"},
     *     summary="Fetch development ideas for client - auth user",
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
     *         description="Development ideas fetched successfully"
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
        $developmentIdeas = DevelopmentIdea::paginate(10);

        return $this->respondOk($developmentIdeas);
    }


    /**
     * @OA\Get (
     *     path="/api/v1/admin/development-ideas",
     *     operationId="Fetch development ideas for admin",
     *     tags={"Development ideas"},
     *     summary="Fetch development ideas for admin",
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
     *         description="Development ideas fetched successfully"
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
        $developmentIdeas = DevelopmentIdea::paginate(10);

        return $this->respondOk($developmentIdeas);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/client/development-ideas",
     *     operationId="Create development idea",
     *     tags={"Development ideas"},
     *     summary="Create development idea",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="201",
     *         description="Development idea created successfully"
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
     *         @OA\JsonContent(ref="#/components/schemas/CreateDevelopmentIdeaVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DevelopmentIdeaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(DevelopmentIdeaRequest $request): \Illuminate\Http\JsonResponse
    {
        $developmentIdea = DevelopmentIdea::create($request->validated() + [
                'user_id' => auth('api')->id()
            ]);

        Notification::send(User::fetchAdmins(), new DevelopmentIdeaNotification($request->validated()));

        return $this->respondCreated(['id' => $developmentIdea->id]);
    }

    /**
     * @OA\Patch (
     *     path="/api/v1/admin/development-ideas/{idea}",
     *     operationId="Patch update development-idea",
     *     tags={"Development ideas"},
     *     summary="Patch update development-idea",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="idea",
     *          description="idea id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Development-idea updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Development-idea not found"
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
     *                  description="Status",
     *                  example="received"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  title="message of faq",
     *                  description="message",
     *                  example="some message"
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param PatchUpdateDevelopmentIdeaRequest $request
     * @param DevelopmentIdea $idea
     * @return \Illuminate\Http\JsonResponse
     */

    public function patchUpdate(PatchUpdateDevelopmentIdeaRequest $request, DevelopmentIdea $idea): \Illuminate\Http\JsonResponse
    {
        $idea->update($request->validated());

        return $this->respondOk(['id' => $idea->id]);
    }

    /**
     * @OA\Put (
     *     path="/api/v1/client/development-ideas/{idea}",
     *     operationId="Update development-idea",
     *     tags={"Development ideas"},
     *     summary="Update development-idea",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="idea",
     *          description="Idea id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Development idea updated successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Development idea not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateDevelopmentIdeaVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DevelopmentIdeaRequest $request
     * @param DevelopmentIdea $idea
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(DevelopmentIdeaRequest $request, DevelopmentIdea $idea): \Illuminate\Http\JsonResponse
    {
        $idea->update($request->validated());

        return $this->respondOk(['id' => $idea->id]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/development-ideas/{idea}",
     *     operationId="Delete development idea by id",
     *     tags={"Development ideas"},
     *     summary="Delete development idea by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *          name="idea",
     *          description="Idea id",
     *          required=true,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="204",
     *         description="Development idea deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Development idea not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DevelopmentIdea $idea
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy(DevelopmentIdea $idea): \Illuminate\Http\JsonResponse
    {
        $idea->delete();

        return $this->respondNoContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/development-ideas",
     *     operationId="Delete development ideas items",
     *     tags={"Development ideas"},
     *     summary="Delete development ideas items",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="204",
     *         description="Development ideas deleted successfully"
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
        DevelopmentIdea::whereIn('id', $request->ids)->delete();

        return $this->respondNoContent();
    }
}
