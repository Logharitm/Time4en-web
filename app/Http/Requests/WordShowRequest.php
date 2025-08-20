<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $word = $this->route('word');
        return auth()->check();
    }

    //
//
//
//        if ($user->role !== 'admin') {
//            if ($word->folder->user_id !== $user->id) {
//                throw new AuthorizationException('Unauthorized to view this word.');
//            }
//        }

    public function rules(): array
    {
        return [];
    }
}

