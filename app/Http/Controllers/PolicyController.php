<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePolicyRequest;
use App\Models\Policy;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class PolicyController extends Controller
{
    use ApiResponse;

    public function show(string $type): JsonResponse
    {
        $policy = Policy::query()->where('type', $type)->firstOrFail();
        return $this->successResponse("تم جلب البيانات بنجاح", $policy);
    }


    public function update(UpdatePolicyRequest $request, string $type): JsonResponse
    {
        $policy = Policy::query()->where('type', $type)->firstOrFail();
        $policy->update(['content' => $request->input('content')]);

        return $this->successResponse("تم تحديث البيانات بنجاح", $policy);
    }
}

