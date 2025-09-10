<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Events\NewUserMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * عرض الرسائل (قابل للفلترة/pagination)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Message::with('sender', 'receiver');

        if ($userId = $request->get('user_id')) {
            $query->where('receiver_id', $userId);
        }

        if ($onlyUnread = $request->boolean('unread')) {
            $query->where('is_read', false);
        }

        $perPage = $request->get('per_page', 20);
        $messages = $query->latest()->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => MessageResource::collection($messages),
            'meta' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
            ],
        ]);
    }

    /**
     * إرسال رسالة (من admin) إلى مستلمين متعددين أو فردي
     * body: { receiver_ids: [1,2], title, body, meta }
     */
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'receiver_ids' => 'required|array|min:1',
            'receiver_ids.*' => 'exists:users,id',
            'title' => 'nullable|string|max:191',
            'body' => 'required|string',
            'meta' => 'nullable|array',
        ]);

        $senderId = auth()->id();

        $created = [];
        foreach ($data['receiver_ids'] as $rid) {
            $msg = Message::create([
                'sender_id' => $senderId,
                'receiver_id' => $rid,
                'title' => $data['title'] ?? null,
                'body' => $data['body'],
                'meta' => $data['meta'] ?? null,
            ]);

            // بث الحدث realtime لكل رسالة
            event(new NewUserMessage($msg));
            $created[] = $msg;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم إرسال الرسائل.',
            'data' => MessageResource::collection(collect($created)),
        ]);
    }

    /**
     * وسم كمقروء
     */
    public function markRead(Message $message): JsonResponse
    {
        if (auth()->id() !== $message->receiver_id && !auth()->user()->is_admin) {
            return response()->json(['message' => 'غير مصرح.'], 403);
        }

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'تم وسم الرسالة كمقروء.']);
    }

    /**
     * وسم كغير مقروء
     */
    public function markUnread(Message $message): JsonResponse
    {
        if (auth()->id() !== $message->receiver_id && !auth()->user()->is_admin) {
            return response()->json(['message' => 'غير مصرح.'], 403);
        }

        $message->update([
            'is_read' => false,
            'read_at' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'تم وسم الرسالة كغير مقروء.']);
    }

    /**
     * حذف رسالة
     */
    public function destroy(Message $message): JsonResponse
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'غير مصرح.'], 403);
        }

        $message->delete();

        return response()->json(['status' => 'success', 'message' => 'تم حذف الرسالة.']);
    }

    /**
     * عداد الرسائل الغير مقروءة للمستخدم الحالي
     */
    public function unreadCount(): JsonResponse
    {
        $count = Message::where('receiver_id', auth()->id())->where('is_read', false)->count();

        return response()->json(['status' => 'success', 'unread' => $count]);
    }
}
