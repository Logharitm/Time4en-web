<?php

namespace App\Http\Middleware;

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

        if ($user->role !== 'user') {
            if (is_null($user->email_verified_at)) {
                return response()->json(['message' => 'يجب تفعيل البريد الإلكتروني أولاً.'], 403);
            }

            $subscription = $user->subscription; // نفترض عندك relation في الموديل User -> subscription()
            if (!$subscription) {
                return response()->json(['message' => 'أنت غير مشترك في أي باقة.'], 403);
            }

            if (is_null($subscription->ends_at) || now()->gt($subscription->ends_at)) {
                return response()->json(['message' => 'انتهت صلاحية اشتراكك.'], 403);
            }

            $usedWords = $user->words()->count() ?? 0;
            $allowedWords = $subscription->words_limit;

            if ($usedWords >= $allowedWords) {
                return response()->json(['message' => 'لقد استهلكت جميع كلمات الباقة الخاصة بك.'], 403);
            }
        }

        return $next($request);
    }
}
