<?php

namespace IdeoLearn\Core\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseHelper
{
    public static function success($data = [], $message = null, $code = Response::HTTP_OK): JsonResponse
    {
        return self::response(true, $data, $message, $code);
    }

    public static function error($name, $errors = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $message = is_array($errors) ? $errors : [$errors];
        return self::response(false, [], $name, $code, $message);
    }

    public static function collection($items, $meta = null, $message = null, $code = Response::HTTP_OK): JsonResponse
    {
        $data = [
            'items' => $items,
            'meta' => $meta,
        ];
        return self::response(true, $data, $message, $code);
    }

    protected static function response(bool $success, $data, ?string $name, int $code, $message = null): JsonResponse
    {
        $response = [
            'success' => $success,
            'name' => $name,
            'message' => $message,
            'data' => $data,
            'status' => $code,
        ];

        // Remove null values
        $response = array_filter($response, fn($value) => !is_null($value));

        self::logResponse($name, $code);

        return response()->json($response, $code);
    }

    protected static function logResponse(?string $message, int $code): void
    {
        if ($code >= 400) {
            Log::error($message ?? 'An error occurred', ['status' => $code]);
        } elseif ($message) {
            Log::info($message, ['status' => $code]);
        }
    }
}

