<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Notification::with(['sender', 'user'])
            ->orderBy('created_at', 'desc');

        $query->when($request->has('search'), function ($q) use ($request) {
            $search = $request->input('search');
            $q->where('message', 'like', "%{$search}%")->orWhere('message_en', 'like', "%{$search}%");
        });

        $query->when($request->has('is_read'), function ($q) use ($request) {
            $isRead = $request->boolean('is_read');
            $q->where('is_read', $isRead);
        });

        $query->when($request->has('recipient'), function ($q) use ($request) {
            $recipient = $request->input('recipient');
            $q->whereHas('user', function ($subQuery) use ($recipient) {
                $subQuery->where('name', 'like', "%{$recipient}%")
                    ->orWhere('email', 'like', "%{$recipient}%");
            });
        });

        $query->when($request->has('start_date'), function ($q) use ($request) {
            $startDate = $request->input('start_date');
            $q->whereDate('created_at', '>=', $startDate);
        });

        $query->when($request->has('end_date'), function ($q) use ($request) {
            $endDate = $request->input('end_date');
            $q->whereDate('created_at', '<=', $endDate);
        });

        $perPage = $request->input('per_page', 20);


        $notifications = $query->paginate($perPage);

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
                'message_en'   => $request->message_en,
            ]);

            $user = User::find($userId);
            if ($user && $user->device_token) {
                if ($user->language == 'ar'){
                    $msg = $request->message;
                }else{
                    $msg = $request->message_en;
                }
                $this->sendFCMNotification(
                    $user->device_token,
                    'New Notification',
                    $msg,
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
        $projectId   = json_decode(file_get_contents(url('time4en-2cf44-163c8c65d3c2.json')), true)['project_id'];

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
        $client->setAuthConfig(url('time4en-2cf44-163c8c65d3c2.json'));

        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithAssertion();
        }

        $tokenData = $client->getAccessToken();
        return $tokenData['access_token'];
    }

}
