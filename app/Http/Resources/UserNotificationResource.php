<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'message'   => $this->message,
            'is_read'   => (bool) $this->is_read,
            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }
}
