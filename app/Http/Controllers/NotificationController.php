<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $notifications = Notification::with(['sender','user'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        return $this->successResponse(
            'Notifications retrieved successfully.',
            NotificationResource::collection($notifications)
        );
    }

    public function store(StoreNotificationRequest $request): JsonResponse
    {
        $senderId = $request->user()->id;

        $notifications = [];
        foreach ($request->user_ids as $userId) {
            $notifications[] = Notification::create([
                'sender_id' => $senderId,
                'user_id'   => $userId,
                'message'   => $request->message,
            ]);
        }

        return $this->successResponse(
            'Notification(s) sent successfully.',
            NotificationResource::collection($notifications)
        );
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        $notification->update(['is_read' => true]);
        return $this->successResponse('Notification marked as read.', new NotificationResource($notification));
    }
}
