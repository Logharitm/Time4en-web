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

        // Apply checks only for normal users
        if ($user->role === 'user') {
            // Check email verification
            if (is_null($user->email_verified_at)) {
                return $this->forbidden('You must verify your email first.');
            }

            // Get the current subscription
            $subscription = Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$subscription) {
                return $this->forbidden('You are not subscribed to any plan.');
            }

            // Check subscription expiration
            if (is_null($subscription->end_date) || now()->gt($subscription->end_date)) {
                $subscription->update(['status' => 'expired']);
                return $this->forbidden('Your subscription has expired.');
            }

            // Check words usage
            $usedWords    = $user->words()->count() ?? 0;
            $allowedWords = $subscription->plan->words_limit;

            if ($usedWords >= $allowedWords) {
                return $this->forbidden('You have used all the words included in your plan.');
            }
        }

        return $next($request);
    }

    /**
     * Helper method to return a forbidden response.
     */
    private function forbidden(string $message): Response
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => []
        ], 403);
    }
}
