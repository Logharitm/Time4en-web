<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'subscription'    => $this->subscription ? [
                'id' => $this->subscription->id,
                'user' => $this->subscription->user ? ['id'=>$this->subscription->user->id,'name'=>$this->subscription->user->name] : null,
                'plan' => $this->subscription->plan ? ['id'=>$this->subscription->plan->id,'name'=>$this->subscription->plan->name] : null,
            ] : null,
            'amount'          => $this->amount,
            'status'          => $this->status,
            'payment_method'  => $this->payment_method,
            'paid_at'         => $this->paid_at,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
