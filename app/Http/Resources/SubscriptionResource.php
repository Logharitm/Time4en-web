<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'user'       => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name] : null,
            'plan'       => $this->plan ? ['id' => $this->plan->id, 'name' => $this->plan->name] : null,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'amount_paid'=> $this->amount_paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
