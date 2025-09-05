<?php
namespace App\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait ApiResponse
{
    protected function successResponse(string $message, $data = [], int $code = 200): JsonResponse
    {
        if ($data instanceof AnonymousResourceCollection) {
            $payload = $data->response()->getData(true);
        } else {
            $payload = ['data' => $data];
        }

        return response()->json([
                'status' => 'success',
                'message' => $message,
            ] + $payload, $code);
    }
}
