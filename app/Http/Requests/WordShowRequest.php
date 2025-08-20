<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $word = $this->route('word');
        if ($user->role !== 'admin') {
            if ($word->folder->user_id !== $user->id) {
                throw new AuthorizationException('Unauthorized to view this word.');
            }
        }
        return auth()->check();
    }


    public function rules(): array
    {
        return [];
    }
}

