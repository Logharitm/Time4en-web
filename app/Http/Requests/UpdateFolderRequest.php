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

        // Admins are always authorized
        if ($user->role === 'admin') {
            return true;
        }

        // Get the folder ID from the route parameter
        // The name 'folder' should match the parameter name in your route definition, e.g., /folders/{folder}
        $folderId = $this->route('folder');

        // If a folder ID is found, check if the user owns it
        if ($folderId) {
            return Folder::where('id', $folderId)->where('user_id', $user->id)->exists();
        }

        // Fallback: If no folder is provided in the route, just check if the user is authenticated.
        // This part might not be needed depending on your application's logic.
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
