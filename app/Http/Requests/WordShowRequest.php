<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Word;
use Illuminate\Auth\Access\AuthorizationException;

class ShowWordRequest extends FormRequest
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

        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
