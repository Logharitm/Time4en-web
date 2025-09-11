<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'message'   => 'required|string|max:1000',
            'user_ids'  => 'required|array|min:1',
            'user_ids.*'=> 'exists:users,id',
        ];
    }
}
