<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WordShowRequest extends FormRequest
{
    public function authorize(): bool
    {
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
