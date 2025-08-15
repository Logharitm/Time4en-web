<?php
namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($message = '', $data = [], $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
