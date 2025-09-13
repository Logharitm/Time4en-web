<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // فقط نطبق الشروط على المستخدمين العاديين
        if ($user->role === 'user') {
            // تحقق من تفعيل البريد
            if (is_null($user->email_verified_at)) {
                return $this->forbidden('يجب تفعيل البريد الإلكتروني أولاً.');
            }

            // اجلب الاشتراك الحالي
            $subscription = Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$subscription) {
                return $this->forbidden('أنت غير مشترك في أي باقة.');
            }

            // تحقق من انتهاء الاشتراك
            if (is_null($subscription->end_date) || now()->gt($subscription->end_date)) {
                $subscription->update(['status' => 'expired']);
                return $this->forbidden('انتهت صلاحية اشتراكك.');
            }

            // تحقق من استهلاك الكلمات
            $usedWords    = $user->words()->count() ?? 0;
            $allowedWords = $subscription->plan->words_limit;

            if ($usedWords >= $allowedWords) {
                return $this->forbidden('لقد استهلكت جميع كلمات الباقة الخاصة بك.');
            }
        }

        return $next($request);
    }

    /**
     * Helper method to return a forbidden response.
     */
    private function forbidden(string $message): Response
    {
        return response()->json(['message' => $message], 403);
    }
}
