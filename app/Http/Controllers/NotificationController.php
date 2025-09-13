<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Google\Client as GoogleClient;

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
            $notification = Notification::create([
                'sender_id' => $senderId,
                'user_id'   => $userId,
                'message'   => $request->message,
            ]);

            $user = User::find($userId);
            if ($user && $user->device_token) {
                $this->sendFCMNotification(
                    $user->device_token,
                    'New Notification',
                    $request->message
                );
            }

            $notifications[] = $notification;
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


    function sendFCMNotification($deviceToken, $title, $body)
    {
        $accessToken = $this->getAccessToken();
        $projectId   = json_decode(file_get_contents(public_path('key.json')), true)['project_id'];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $data = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    "title" => $title,
                    "body"  => $body,
                ],
            ]
        ];

        $headers = [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    function getAccessToken(): string
    {
        $client = new GoogleClient();
        $client->setAuthConfig(public_path('key.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithAssertion();
        }

        $tokenData = $client->getAccessToken();
        return $tokenData['access_token'];
    }

}
