<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Folder;
use App\Models\Contact;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Practice;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            [
                'icon' => 'tabler-folder',
                'color' => 'primary',
                'title' => 'Folders',
                'value' => Folder::count(),
            ],
            [
                'icon' => 'tabler-file-word',
                'color' => 'success',
                'title' => 'Words',
                'value' => Word::count(),
            ],
            [
                'icon' => 'tabler-users',
                'color' => 'secondary',
                'title' => 'Users',
                'value' => User::count()
            ],
            [
                'icon' => 'tabler-shield-question',
                'color' => 'info',
                'title' => 'Practice',
                'value' => Practice::count()
            ],
            [
                'icon' => 'tabler-brand-messenger',
                'color' => 'warning',
                'title' => 'Messages',
                'value' => Contact::count()
            ],
            [
                'icon' => 'tabler-plant-2',
                'color' => 'error',
                'title' => 'Plans',
                'value' => Plan::count()
            ],
            [
                'icon' => 'tabler-paywall',
                'color' => 'info',
                'title' => 'Subscriptions',
                'value' => Subscription::count()
            ],
            [
                'icon' => 'tabler-brand-cashapp',
                'color' => 'success',
                'title' => 'Payments',
                'value' => Payment::count()
            ],
        ]);
    }

    public function plans()
    {
        $totalSubscriptions = Subscription::where('status', 'active')->count();

        if ($totalSubscriptions === 0) {
            return response()->json([]);
        }

        $plans = Plan::withCount(['subscriptions' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        $data = $plans->map(function ($plan) use ($totalSubscriptions) {
            $count = $plan->subscriptions_count;

            return [
                'icon' => 'tabler-currency-dollar', // أو تغيرها حسب نوع الخطة
                'title' => $plan->name,
                'subscribers' => $count,
                'percentage' => round(($count / $totalSubscriptions) * 100, 1),
            ];
        });

        return response()->json($data);
    }
}
