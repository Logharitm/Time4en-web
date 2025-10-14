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
                'icon' => 'tabler-folders',
                'color' => 'primary',
                'title' => 'المجلدات',
                'title_en' => 'folders',
                'value' => Folder::count(),
            ],
            [
                'icon' => 'tabler-file-word',
                'color' => 'success',
                'title' => 'الكلمات',
                'title_en' => 'words',
                'value' => Word::count(),
            ],
            [
                'icon' => 'tabler-users',
                'color' => 'secondary',
                'title' => 'المستخدمين',
                'title_en' => 'users',
                'value' => User::count()
            ],
            [
                'icon' => 'tabler-shield-question',
                'color' => 'info',
                'title' => 'الاختبارات',
                'title_en' => 'practices',
                'value' => Practice::count()
            ],
            [
                'icon' => 'tabler-brand-messenger',
                'color' => 'warning',
                'title' => 'الرسائل الواردة',
                'title_en' => 'messages',
                'value' => Contact::count()
            ],
            [
                'icon' => 'tabler-plant-2',
                'color' => 'error',
                'title' => 'باقات الاشتراك',
                'title_en' => 'plans',
                'value' => Plan::count()
            ],
            [
                'icon' => 'tabler-paywall',
                'color' => 'info',
                'title' => 'الاشتراكات',
                'title_en' => 'subscriptions',
                'value' => Subscription::count()
            ],
            [
                'icon' => 'tabler-brand-cashapp',
                'color' => 'success',
                'title' => 'المدفوعات',
                'title_en' => 'payments',
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
