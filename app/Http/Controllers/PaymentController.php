<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Payment::with(['subscription','subscription.user','subscription.plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_id')) {
            $query->whereHas('subscription.plan', function ($q) use ($request) {
                $q->where('id', $request->plan_id);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('subscription.user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('paid_from')) {
            $query->whereDate('paid_at', '>=', $request->paid_from);
        }

        if ($request->filled('paid_to')) {
            $query->whereDate('paid_at', '<=', $request->paid_to);
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->latest('paid_at'); // default sort by paid_at desc
        }

        $payments = $query->paginate($request->get('per_page', 20));

        return $this->successResponse(
            'Payments retrieved successfully.',
            PaymentResource::collection($payments)
        );
    }


    public function show(Payment $payment): JsonResponse
    {
        return $this->successResponse('Payment retrieved successfully.', new PaymentResource($payment));
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($request->validated());
        return $this->successResponse('Payment created successfully.', new PaymentResource($payment));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): JsonResponse
    {
        $payment->update($request->validated());
        return $this->successResponse('Payment updated successfully.', new PaymentResource($payment));
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();
        return $this->successResponse('Payment deleted successfully.');
    }
}
