<?php

namespace Gentcmen\Http\Controllers\API;

use Gentcmen\Http\Controllers\ApiController;
use Gentcmen\Notifications\MarkAsReadAllNotifications;
use Gentcmen\Notifications\NotificationRemoved;
use Illuminate\Http\Response;
use Gentcmen\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Gentcmen\Notifications\ReadAtNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/admin/notifications",
     *     operationId="Fetch all notifications",
     *     tags={"Notifications"},
     *     summary="Fetch all notifications. Sent to the channel",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="Notifications fetched successfully"
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
        $allNotifications = DatabaseNotification::orderBy('created_at', 'DESC')->paginate(20);

        return $this->respondOk($allNotifications);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/notifications/{notification}",
     *     operationId="Mark notification as read",
     *     tags={"Notifications"},
     *     summary="Mark notification as read",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *        name="notification",
     *        description="Notification id",
     *        required=true,
     *        in="path",
     *        example="1",
     *        @OA\Schema(
     *            type="integer"
     *        )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Notification marked as read successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param DatabaseNotification $notification
     * @return \Illuminate\Http\JsonResponse
     */

    public function markNotificationAsRead(DatabaseNotification $notification): \Illuminate\Http\JsonResponse
    {
        $notification->markAsRead();

	    Notification::send(User::fetchAdmins(), new ReadAtNotification(
            [
                'id_read' => $notification->id,
                'read_at' => $notification->read_at
            ]
        ));

        return $this->respondOk($notification);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/notifications/mark/markAllNotifications",
     *     operationId="Mark all notifications as read",
     *     tags={"Notifications"},
     *     summary="Mark all notifications as read",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Response(
     *         response="200",
     *         description="All notifications marked as read successfully"
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

    public function markAllNotifications(): \Illuminate\Http\JsonResponse
    {
        $notifications = DatabaseNotification::get();

        $notifications->markAsRead();

        Notification::send(User::fetchAdmins(), new MarkAsReadAllNotifications());

        return $this->respondOk();
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/admin/notifications/{notification}",
     *     operationId="Delete notification by id",
     *     tags={"Notifications"},
     *     summary="Delete notification by id",
     *     security={ {"bearerAuth": {}} },
     *     @OA\Parameter(
     *         name="notification",
     *         in="path",
     *         description="Faq's id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Notification is deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Something went wrong"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Notification not found with provided id"
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroyNotification(DatabaseNotification $notification): \Illuminate\Http\JsonResponse
    {
        $notification->delete();

        Notification::send(User::fetchAdmins(), new NotificationRemoved(
            [
                'id' => $notification->id,
            ]
        ));

        return $this->respondOk(['id' => $notification->id]);
    }
}
