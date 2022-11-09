<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Http\Requests\SendIdea\SendIdeaRequest;
use Gentcmen\Models\User;
use Gentcmen\Notifications\SendIdeaNotification;
use Illuminate\Support\Facades\Notification;

class SendIdeaController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/client/ideas",
     *     operationId="Send idea",
     *     tags={"Ideas"},
     *     summary="Send some idea to developers",
     *     @OA\Response(
     *         response="200",
     *         description="Email with your idea was sent successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SendIdeaVirtualBody")
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function sendIdea(SendIdeaRequest $request): \Illuminate\Http\JsonResponse
    {
        $ideaInformation = [
            'theme' => $request->theme,
            'content' =>  $request->content,
            'user_email' =>  $request->user_email,
            'name' =>  $request->user_name,
        ];

        Notification::send(User::fetchAdmins(), new SendIdeaNotification((object) $ideaInformation));

        return $this->respondOk();
    }
}
