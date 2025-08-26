<?php

namespace App\Http\Controllers;

use App\Http\Resources\PracticeReportResource;
use App\Models\Folder;
use App\Models\Practice;
use App\Models\PracticeAnswer;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PracticeController extends Controller
{
    public function createQuiz(Request $request): JsonResponse
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
            'num_questions' => 'nullable|integer|min:1',
        ]);

        $folderId = $request->input('folder_id');
        $numQuestions = $request->input('num_questions');
        $userId = auth()->id();

        // جلب الكلمات بناءً على المجلد أو جميع المجلدات
        $query = Word::with('folder')
            ->whereHas('folder', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });

        if ($folderId) {
            $query->where('folder_id', $folderId);
        }

        $allWords = $query->get();

        // التحقق من وجود كلمات كافية
        if ($allWords->count() < 4) {
            return response()->json([
                'message' => 'لا يوجد كلمات كافية لإنشاء اختبار. يجب أن يكون هناك 4 كلمات على الأقل.',
            ], 400);
        }

        // تحديد عدد الأسئلة
        $totalWords = $numQuestions && $numQuestions <= $allWords->count()
            ? $numQuestions
            : $allWords->count();

        // خلط الكلمات لاختيار الأسئلة بشكل عشوائي
        $questionsWords = $allWords->shuffle()->take($totalWords);

        // إنشاء جلسة الاختبار في قاعدة البيانات
        $practice = Practice::create([
            'user_id' => $userId,
            'folder_id' => $folderId,
            'total_words' => $totalWords,
        ]);

        $questions = [];

        foreach ($questionsWords as $word) {
            // جلب 3 ترجمات خاطئة بشكل عشوائي من الكلمات الأخرى
            $incorrectTranslations = $allWords
                ->where('id', '!=', $word->id)
                ->shuffle()
                ->take(3)
                ->pluck('translation')
                ->all();

            // إضافة الإجابة الصحيحة إلى الخيارات
            $options = array_merge($incorrectTranslations, [$word->translation]);

            // خلط الخيارات لترتيبها بشكل عشوائي
            shuffle($options);

            $questions[] = [
                'word_id' => $word->id,
                'word' => $word->word,
                'question' => "ما هي ترجمة كلمة '{$word->word}'؟",
                'correct_answer' => $word->translation,
                'options' => $options,
            ];
        }

        return response()->json([
            'message' => 'تم إنشاء الاختبار بنجاح.',
            'practice_id' => $practice->id,
            'questions' => $questions,
        ]);
    }

    /**
     * حفظ إجابة المستخدم على سؤال معين.
     */
    public function submitAnswer(Request $request): JsonResponse
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'practice_id' => 'required|exists:practices,id',
            'word_id' => 'required|exists:words,id',
            'selected_option' => 'required|string',
        ]);

        $practiceId = $request->input('practice_id');
        $wordId = $request->input('word_id');
        $selectedOption = $request->input('selected_option');

        // جلب الكلمة الصحيحة للتحقق من الإجابة
        $word = Word::find($wordId);
        if (!$word) {
            return response()->json(['message' => 'الكلمة غير موجودة.'], 404);
        }

        // التحقق مما إذا كانت الإجابة صحيحة
        $isCorrect = $word->translation === $selectedOption;

        // حفظ الإجابة في قاعدة البيانات
        PracticeAnswer::create([
            'practice_id' => $practiceId,
            'word_id' => $wordId,
            'is_correct' => $isCorrect,
            'selected_option' => $selectedOption,
        ]);

        return response()->json([
            'message' => 'تم تسجيل الإجابة بنجاح.',
            'is_correct' => $isCorrect,
        ]);
    }

    /**
     * إظهار تقرير الاختبار النهائي.
     */
    public function showReport(Practice $practice): JsonResponse
    {
        // التأكد من أن المستخدم يمتلك هذه الجلسة
        if ($practice->user_id !== auth()->id()) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا التقرير.'], 403);
        }

        // جلب جميع الإجابات للجلسة
        $answers = $practice->answers()->with('word')->get();

        // حساب الإحصائيات
        $correctCount = $answers->where('is_correct', true)->count();
        $wrongCount = $answers->where('is_correct', false)->count();
        $totalCount = $practice->total_words;

        // حساب النسبة المئوية
        $percentage = $totalCount > 0 ? round(($correctCount / $totalCount) * 100, 2) : 0;

        // تحديث جدول practices بالإحصائيات النهائية
        $practice->update([
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongCount,
        ]);

        // تجميع الإجابات الصحيحة والخاطئة
        $correctAnswers = $answers->filter(function ($answer) {
            return $answer->is_correct;
        });

        $wrongAnswers = $answers->filter(function ($answer) {
            return !$answer->is_correct;
        });

        return response()->json([
            'message' => 'تم استرجاع التقرير بنجاح.',
            'report' => [
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
            ]
        ]);
    }
}
