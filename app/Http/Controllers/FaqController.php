<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Faq::query();


        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                    ->orWhere('answer', 'like', "%{$search}%")
                    ->orWhere('question_en', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%");
            });
        }

        $faqs = $query->paginate($request->get('per_page', 20));
        return $this->successResponse("تم جلب الأسئلة", $faqs);
    }

    public function store(StoreFaqRequest $request): JsonResponse
    {
        $faq = Faq::query()->create($request->validated());
        return $this->successResponse("تم إنشاء السؤال", $faq);
    }

    public function show(Faq $faq): JsonResponse
    {
        return $this->successResponse("تم جلب السؤال", $faq);
    }

    public function update(UpdateFaqRequest $request, Faq $faq): JsonResponse
    {
        $faq->update($request->validated());
        return $this->successResponse("تم تحديث السؤال", $faq);
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();
        return $this->successResponse("تم حذف السؤال");
    }
}
