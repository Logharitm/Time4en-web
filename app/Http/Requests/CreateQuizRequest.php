<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Folder;

class CreateQuizRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $folderId = $this->input('folder_id');

        if (!$folderId) {
            return true;
        }

        return Folder::where('id', $folderId)
            ->where('user_id', $this->user()->id)
            ->exists();
    }

    /**
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'folder_id' => 'nullable|exists:folders,id',
            'num_questions' => 'nullable|integer|min:1',
        ];
    }

    /**
     * @return void
     *
     * @throws AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException('غير مصرح لك بالوصول إلى هذا المجلد.');
    }
}
