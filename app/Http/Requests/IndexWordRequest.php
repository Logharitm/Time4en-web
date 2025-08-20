<?php

namespace App\Http\Requests;

use App\Models\Folder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class IndexWordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->role === 'admin') {
            return true;
        }

        $folderId = $this->input('folder_id') ?? $this->route('folder');

        if ($folderId) {
            $folder = Folder::find($folderId);

            if ($folder && $folder->user_id === $user->id) {
                return true;
            }
        }

        return false;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Unauthorized to view this word.');
    }

    public function rules(): array
    {
        return [
            'folder_id'   => 'nullable|integer|exists:folders,id',
            'search'      => 'nullable|string|max:255',
            'sort_by'     => 'nullable|in:word,translation,example_sentence,created_at,updated_at',
            'sort_order'  => 'nullable|in:asc,desc',
            'per_page'    => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'folder_id.exists' => 'The selected folder does not exist.',
            'sort_by.in'       => 'Invalid sort column.',
            'sort_order.in'    => 'Sort order must be asc or desc.',
        ];
    }
}
