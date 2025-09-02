<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'subscription_id' => 'sometimes|required|exists:subscriptions,id',
            'amount'          => 'sometimes|required|numeric|min:0',
            'status'          => 'sometimes|required|in:pending,completed,failed',
            'payment_method'  => 'nullable|string|max:50',
            'paid_at'         => 'nullable|date',
        ];
    }
}
