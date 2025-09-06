<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'user'       => $this->user ? ['id' => $this->user->id, 'name' => $this->user->name, 'email' => $this->user->email, 'avatar' => $this->user->avater ] : null,
            'plan'       => $this->plan ? ['id' => $this->plan->id, 'name' => $this->plan->name, 'price' => $this->plan->price , 'description' => $this->plan->description, 'duration_months' => $this->plan->duration_months, 'words_limit' => $this->plan->words_limit ] : null,
            'payment'       => $this->payment ? ['id' => $this->payment->id, 'amount' => $this->payment->amount, 'status' => $this->payment->status , 'payment_method' => $this->payment->payment_method, 'paid_at' => $this->payment->paid_at] : null,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'amount_paid'=> $this->amount_paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
