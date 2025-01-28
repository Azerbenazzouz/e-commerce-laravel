<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{

    public static function ok($data = null, string $message = '', int $code = 200) {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()
        ], $code);
    }

    public static function error(array $errors = [], string $message = '', int $code = 400) {
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()
        ], $code);
    }

    public static function message(string $message, int $code = 200) {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            'timestamp' => now()
        ], $code);
    }

    public function toArray(Request $request): array {
        return parent::toArray($request);
    }
}
