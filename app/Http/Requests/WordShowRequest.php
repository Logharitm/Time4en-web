<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    //        $user = $this->user();
//        $word = $this->route('word');
//
//        if ($user->role !== 'admin') {
//            if ($word->folder->user_id !== $user->id) {
//                throw new AuthorizationException('Unauthorized to view this word.');
//            }
//        }

    public function rules(): array
    {
        return [
            'word' => 'sometimes|string|max:255',
            'translation' => 'sometimes|required|string|max:255',
            'example_sentence' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav|max:20480',
        ];
    }
}

