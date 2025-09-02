<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'plan_id'   => 'required|exists:plans,id',
            'start_date'=> 'required|date',
            'end_date'  => 'required|date|after_or_equal:start_date',
            'status'    => 'required|in:active,expired,canceled',
            'amount_paid'=> 'nullable|numeric|min:0',
        ];
    }
}
