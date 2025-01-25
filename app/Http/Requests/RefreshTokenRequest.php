<?php

namespace App\Http\Requests;

use App\Repositories\RefreshTokenRepositroy;

class RefreshTokenRequest extends BaseRequest {

    private $refreshTokenRepository;
    public function __construct() {
       $this->refreshTokenRepository = app(RefreshTokenRepositroy::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refreshToken' => 'required|string'
        ];
    }

    // custom check
    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $refreshTokenValue = $this->input('refreshToken');
            $refreshToken = $this->refreshTokenRepository->findRefreshTokenValid($refreshTokenValue);

            if(!$refreshToken) {
                $validator->errors()->add('refreshToken', 'Refresh Token is invalid');
            }
        });
    }
}
