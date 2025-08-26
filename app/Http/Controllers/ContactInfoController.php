<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateContactInfoRequest;
use App\Models\ContactInfo;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ContactInfoController extends Controller
{
    use ApiResponse;

    /**
     * Get contact info (anyone can see it).
     */
    public function show(): JsonResponse
    {
        $info = ContactInfo::first();
        return $this->successResponse('تم جلب بيانات الاتصال بنجاح', $info);
    }

    /**
     * Update contact info (admin only).
     */
    public function update(UpdateContactInfoRequest $request): JsonResponse
    {
        $info = ContactInfo::first(); // عندنا سجل واحد فقط
        $info->update($request->validated());

        return $this->successResponse('تم تحديث بيانات الاتصال بنجاح', $info);
    }
}

