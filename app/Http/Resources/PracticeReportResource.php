<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PracticeReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $correctCount = $this->answers->where('is_correct', true)->count();
        $wrongCount = $this->answers->where('is_correct', false)->count();
        $totalCount = $this->total_words;
        $percentage = $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 2) : 0;

        $correctAnswers = $this->answers->filter(function ($answer) {
            return $answer->is_correct;
        });

        $wrongAnswers = $this->answers->filter(function ($answer) {
            return !$answer->is_correct;
        });

        return [
            'total_questions' => $totalCount,
            'correct_answers_count' => $correctCount,
            'wrong_answers_count' => $wrongCount,
            'score_percentage' => $percentage,
            'correct_answers' => $correctAnswers->map(function ($answer) {
                return [
                    'question' => "ما هي ترجمة كلمة '{$answer->word->word}'؟",
                    'your_answer' => $answer->selected_option,
                    'correct_answer' => $answer->word->translation,
                ];
            }),
            'wrong_answers' => $wrongAnswers->map(function ($answer) {
                return [
                    'word' => $answer->word->word,
                    'correct_translation' => $answer->word->translation,
                    'your_answer' => $answer->selected_option,
                ];
            }),
        ];
    }
}
