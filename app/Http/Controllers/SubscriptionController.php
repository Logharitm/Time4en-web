<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class SubscriptionController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Subscription::with(['user','plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->latest();
        }

        $subscriptions = $query->paginate($request->get('per_page', 10));

        return $this->successResponse(
            'Subscriptions retrieved successfully.',
            SubscriptionResource::collection($subscriptions)
        );
    }


    public function show(Subscription $subscription): JsonResponse
    {
        return $this->successResponse('Subscription retrieved successfully.', new SubscriptionResource($subscription));
    }

    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $subscription = Subscription::create($request->validated());
        return $this->successResponse('Subscription created successfully.', new SubscriptionResource($subscription));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $subscription->update($request->validated());
        return $this->successResponse('Subscription updated successfully.', new SubscriptionResource($subscription));
    }

    public function destroy(Subscription $subscription): JsonResponse
    {
        $subscription->delete();
        return $this->successResponse('Subscription deleted successfully.');
    }
}
