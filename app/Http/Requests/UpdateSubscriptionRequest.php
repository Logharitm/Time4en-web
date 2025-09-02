<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'sometimes|required|exists:users,id',
            'plan_id'   => 'sometimes|required|exists:plans,id',
            'start_date'=> 'sometimes|required|date',
            'end_date'  => 'sometimes|required|date|after_or_equal:start_date',
            'status'    => 'sometimes|required|in:active,expired,canceled',
            'amount_paid'=> 'nullable|numeric|min:0',
        ];
    }
}
