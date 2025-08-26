<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\Contact;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    use ApiResponse;

    public function store(SendMessageRequest $request): JsonResponse
    {
        $message = Contact::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ]);

        return $this->successResponse("تم إرسال الرسالة بنجاح", $message);
    }

    public function index(Request $request): JsonResponse
    {
        $messages = Contact::query()->paginate($request->get('per_page', 20));
        return $this->successResponse("تم جلب الرسائل", $messages);
    }

    public function destroy(Contact $message): JsonResponse
    {
        $message->delete();
        return $this->successResponse("تم حذف الرسالة");
    }
}
