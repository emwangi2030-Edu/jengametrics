<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        array $meta = [],
        ?string $message = null,
        int $status = 200
    ): JsonResponse {
        $payload = ['data' => $data];
        if (! empty($meta)) {
            $payload['meta'] = $meta;
        }
        if ($message) {
            $payload['message'] = $message;
        }

        return response()->json($payload, $status);
    }

    public static function error(
        string $code,
        string $message,
        int $status,
        mixed $details = null
    ): JsonResponse {
        $payload = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        if ($details !== null) {
            $payload['error']['details'] = $details;
        }

        return response()->json($payload, $status);
    }
}

