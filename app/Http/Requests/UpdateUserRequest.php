<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin'; // only admins can update users
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name'                  => 'sometimes|required|string|max:255',
            'email'                 => 'sometimes|required|email|unique:users,email,' . $userId,
            'password'              => 'nullable|string|min:8',
            'role'                  => 'sometimes|required|in:admin,user',
            'language'              => 'nullable|string|max:10',
            'device_token'          => 'nullable|string',
            'avatar' => 'nullable|file|image|mimes:jpg,gif,png|max:2048', // 2MB
            'subscription_plan'     => 'nullable|string|max:255',
            'subscription_expires_at' => 'nullable|date',
        ];
    }
}
