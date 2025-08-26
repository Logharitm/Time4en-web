<?php

namespace App\Http\Requests;

use App\Models\Practice;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $practiceId = $this->input('practice_id');

        if (!$practiceId) {
            return false;
        }

        $practice = Practice::find($practiceId);

        // Ensure the practice exists and belongs to the current user
        return $practice && $practice->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'practice_id' => 'required|integer|exists:practices,id',
            'word_id' => 'required|integer|exists:words,id',
            'selected_option' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'practice_id.required' => 'يجب تحديد السؤال.',
            'practice_id.exists' => 'السؤال غير موجود.',
            'word_id.required' => 'يجب تحديد الكلمة.',
            'word_id.exists' => 'الكلمة غير موجودة.',
            'selected_option.required' => 'يجب اختيار الإجابة.',
        ];
    }
}
