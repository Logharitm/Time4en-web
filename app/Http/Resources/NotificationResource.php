<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'message'   => $this->message,
            'is_read'   => (bool) $this->is_read,
            'sender'    => $this->sender ? new UserResource($this->sender) : null,
            'user'      => new UserResource($this->user),
            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }
}
