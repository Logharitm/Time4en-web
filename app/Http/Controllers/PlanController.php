<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class PlanController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Plan::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $plans = $query->paginate($request->get('per_page', 20));

        return $this->successResponse(
            'Plans retrieved successfully.',
            PlanResource::collection($plans)
        );
    }

    public function store(StorePlanRequest $request): JsonResponse
    {
        $plan = Plan::create($request->validated());
        return $this->successResponse('Plan created successfully.', new PlanResource($plan), 200);
    }

    public function show(Plan $plan): JsonResponse
    {
        return $this->successResponse('Plan retrieved successfully.', new PlanResource($plan));
    }

    public function update(UpdatePlanRequest $request, $planId): JsonResponse
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->subscriptions()->exists()) {
            return $this->errorResponse('This plan has active subscriptions and cannot be updated.', 403);
        }
dd($request->validated());
        $plan->update($request->validated());
        return $this->successResponse('Plan updated successfully.', new PlanResource($plan));
    }

    public function destroy(Plan $plan): JsonResponse
    {
        if ($plan->subscriptions()->exists()) {
            return $this->errorResponse('This plan has subscriptions and cannot be deleted.', 403);
        }

        $plan->delete();
        return $this->successResponse('Plan deleted successfully.');
    }
}
