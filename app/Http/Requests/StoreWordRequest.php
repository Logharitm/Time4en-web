<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'folder_id' => [
                'required',
                Rule::exists('folders', 'id')->when($user->role !== 'admin', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ],
            'word' => 'required|string|max:255',
            'translation' => 'required|string|max:255',
            'example_sentence' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav|max:20480',
        ];
    }
}
