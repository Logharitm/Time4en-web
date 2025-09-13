<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\User;

class AssignSubscription
{
    public function handle(UserRegistered $event)
    {
        $user = $event->user;
        $plan = $event->plan;
        $payment = $event->payment;

        $this->cancelOldSubscriptions($user);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addMonths($plan->duration_months),
            'status' => 'active',
            'amount_paid' => $payment->amount,
        ]);

        Payment::create([
            'subscription_id' => $subscription->id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'status' => $payment->status,
            'paid_at' => now()
        ]);
    }

    public function cancelOldSubscriptions(User $user): void
    {
        Subscription::query()
            ->where('user_id',$user->id)
            ->update(['status' => 'canceled']);
    }
}
