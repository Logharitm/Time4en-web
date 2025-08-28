<?php

namespace App\Http\Requests;

use App\Models\Folder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user->role === 'admin') {
            return true;
        }

        $folder = $this->route('folder');

        if ($folder) {
            return $folder->user_id === $user->id;
        }

        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
