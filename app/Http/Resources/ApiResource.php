<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{

    public static function ok(array $data = [], string $message = '', int $code = 200) {
        return [
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()
        ];
    }

    public static function error(array $errors = [], string $message = '', int $code = 400) {
        return [
            'status' => false,
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()
        ];
    }

    public static function message(string $message, int $code = 200) {
        return [
            'status' => $code === 200,
            'message' => $message,
            'code' => $code,
            'timestamp' => now()
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
