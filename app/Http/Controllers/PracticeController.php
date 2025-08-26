<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuizRequest;
use App\Http\Requests\SubmitAnswerRequest;
use App\Http\Resources\PracticeReportResource;
use App\Models\Practice;
use App\Models\PracticeAnswer;
use App\Models\Word;
use App\Traits\ApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    use ApiResponse;

    public function createQuiz(CreateQuizRequest $request): JsonResponse // Updated
    {
        $folderId = $request->input('folder_id');
        $numQuestions = $request->input('num_questions');
        $userId = auth()->id();

        $query = Word::with('folder')
            ->whereHas('folder', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });

        if ($folderId) {
            $query->where('folder_id', $folderId);
        }

        $allWords = $query->get();

        if ($allWords->count() < 4) {
            throw new HttpException(400, 'لا يوجد كلمات كافية لإنشاء اختبار. يجب أن يكون هناك 4 كلمات على الأقل.');
        }

        $totalWords = $numQuestions && $numQuestions <= $allWords->count()
            ? $numQuestions
            : $allWords->count();


        $questionsWords = $allWords->shuffle()->take($totalWords);

        $practice = Practice::create([
            'user_id' => $userId,
            'folder_id' => $folderId,
            'total_words' => $totalWords,
            'correct_answers' => 0,
            'wrong_answers' => 0,
        ]);

        $questions = [];

        foreach ($questionsWords as $word) {
            $incorrectTranslations = $allWords
                ->where('id', '!=', $word->id)
                ->shuffle()
                ->take(3)
                ->pluck('translation')
                ->all();

            $options = array_merge($incorrectTranslations, [$word->translation]);

            shuffle($options);

            $questions[] = [
                'word_id' => $word->id,
                'word' => $word->word,
                'question' => "ما هي ترجمة كلمة '{$word->word}'؟",
                'correct_answer' => $word->translation,
                'options' => $options,
            ];


            PracticeAnswer::create([
                'practice_id' => $practice->id,
                'word_id' => $word->id,
            ]);

        }

        return $this->successResponse(
            'تم إنشاء الاختبار بنجاح',
            ['practice_id' => $practice->id, 'questions' => $questions]
        );
    }


    public function submitAnswer(SubmitAnswerRequest $request): JsonResponse
    {
        $practiceId = $request->validated()['practice_id'];
        $wordId = $request->validated()['word_id'];
        $selectedOption = $request->validated()['selected_option'];

        $word = Word::findOrFail($wordId);

        $isCorrect = $word->translation === $selectedOption;

        PracticeAnswer::query()
            ->where('practice_id', $practiceId)
            ->where('word_id', $wordId)
            ->whereNull('is_correct')
            ->update([
                'is_correct' => $isCorrect,
                'selected_option' => $selectedOption,
            ]);

        return $this->successResponse(
            'تم تسجيل الإجابة بنجاح.',
            $isCorrect,
        );
    }

    /**
     * إظهار تقرير الاختبار النهائي.
     */
    public function showReport(Practice $practice): JsonResponse
    {
        if ($practice->user_id !== auth()->id()) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا التقرير.'], 403);
        }

        $correctCount = $practice->answers()->where('is_correct', true)->count();
        $wrongCount = $practice->answers()->where('is_correct', false)->count();

        $practice->update([
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
        ]);

        return response()->json([
            'message' => 'تم استرجاع التقرير بنجاح.',
            'report' => new PracticeReportResource($practice)
        ]);
    }
}
