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

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('subscription_id')) {
            $query->where('subscription_id', $request->subscription_id);
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
