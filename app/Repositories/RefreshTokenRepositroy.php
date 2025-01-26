<?php
namespace App\Repositories;

use App\Models\RefreshToken;

class RefreshTokenRepositroy extends BaseRepositroy {
    private $model;

    public function __construct(RefreshToken $model) {
        parent::__construct($model);
        $this->model = $model;
    }

    public function findRefreshTokenValid($refreshToken) {
        return $this->model
            ->where('refresh_token', $refreshToken)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function deleteByUserId(int $userId = 0) {
        return $this->model
            ->where('user_id', $userId)
            ->delete();
    }

}
