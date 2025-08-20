<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
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
        return [
            'word' => 'sometimes|required|string|max:255',
            'translation' => 'sometimes|required|string|max:255',
            'example_sentence' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav|max:20480',
        ];
    }
}
