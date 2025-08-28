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

        $folderId = $this->route('folder');

        if ($folderId) {
            return true;
            //return Folder::where('id', $folderId)->where('user_id', $user->id)->exists();
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
