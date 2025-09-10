<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewUserMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): PrivateChannel|array
    {
        return new PrivateChannel('user.'.$this->message->receiver_id);
    }

    public function broadcastWith(): array
    {
        // تأكد أنّ الـ MessageResource قابل للاستخدام هنا إن أردت
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender?->name,
            'title' => $this->message->title,
            'body' => $this->message->body,
            'meta' => $this->message->meta,
            'is_read' => (bool)$this->message->is_read,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewUserMessage';
    }
}
