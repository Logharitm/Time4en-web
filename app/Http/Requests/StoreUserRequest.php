<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin'; // only admins can create users
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8',
            'role'                  => 'required|in:admin,user',
            'language'              => 'nullable|string|max:10',
            'avatar'                => 'nullable|file|image|mimes:jpg,gif,png|max:2048', // 2MB
            'subscription_plan'     => 'nullable|string|max:255',
            'subscription_expires_at' => 'nullable|date',
        ];
    }
}
