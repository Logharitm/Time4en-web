<?php

namespace App\Http\Requests;

use App\Models\Folder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ShowFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if ($user->role === 'admin') {
            return true;
        }

        $folderId = $this->input('folder_id') ?? $this->route('folder');

        if ($folderId) {
            return Folder::where('id', $folderId)->where('user_id', $user->id)->exists();
        }

        return auth()->check();
    }

    public function rules(): array
    {
        return [];
    }
}
