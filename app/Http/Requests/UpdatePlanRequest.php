<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name'            => 'sometimes|required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'sometimes|required|numeric|min:0',
            'duration_months' => 'sometimes|required|integer|min:1',
            'words_limit'     => 'sometimes|required|integer|min:0',
        ];
    }
}
