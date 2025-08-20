<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Word;

class ShowWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $word = $this->route('word');

        if ($user->role === 'admin') {
            return true;
        }

        return $word->folder->user_id === $user->id;
    }

    public function rules(): array
    {
        return [];
    }
}
