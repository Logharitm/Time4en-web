<?php
namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        MethodNotAllowedHttpException::class => 'handleMethodNotAllowedException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
        ErrorException::class => 'handleErrorException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException|AccessDeniedHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Authentication failed');

        $code = 401;
        $message = 'Authentication required. Please provide valid credentials.';
        $errors = [];

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Authorization failed');

        $code = 403;
        $message = 'You do not have permission to perform this action.';
        $errors = [];

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $e,
        Request $request
    ): JsonResponse {
        $errors = [];

        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        $this->logException($e, 'Validation failed', ['errors' => $errors]);

        $code = 422;
        $message = 'The provided data is invalid.';

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }


    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException|NotFoundHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Resource not found');

        $code = 404;
        $message = $e instanceof ModelNotFoundException
            ? 'The requested resource was not found.'
            : "The requested endpoint '{$request->getRequestUri()}' was not found.";

        $errors = [];

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Handle method not allowed exceptions
     */
    public function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Method not allowed');

        $code = 405;
        $message = "The {$request->method()} method is not allowed for this endpoint.";
        $errors = [
            'allowed_methods' => $e->getHeaders()['Allow'] ?? 'Unknown',
        ];

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'HTTP exception occurred');

        $code = $e->getStatusCode();
        $message = $e->getMessage() ?: 'An HTTP error occurred.';
        $errors = [];

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Database query failed', ['sql' => $e->getSql()]);

        $errorCode = $e->errorInfo[1] ?? null;

        switch ($errorCode) {
            case 1451: // Foreign key constraint violation
                $code = 409;
                $message = 'Cannot delete this resource because it is referenced by other records.';
                $errors = [];
                break;

            case 1062: // Duplicate entry
                $code = 409;
                $message = 'A record with this information already exists.';
                $errors = [];
                break;

            default:
                $code = 500;
                $message = 'A database error occurred. Please try again later.';
                $errors = [];
                break;
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Log exception with context
     */
    private function logException(Throwable $e, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
        ], $context);

        Log::warning($message, $logContext);
    }

    public function handleErrorException(ErrorException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Error exception occurred');

        return response()->json([
            'status' => 'error',
            'message' => 'A server error occurred.',
            'errors' => app()->environment('local') ? [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : [],
        ], 500);
    }
}
