<?php
namespace App\Service\Impl;

use App\Repositories\RefreshTokenRepositroy;
use Illuminate\Support\Facades\DB;

class RefreshTokenService {
    
    private $refreshTokenRepo;

    public function __construct(RefreshTokenRepositroy $refreshTokenRepo) {
        $this->refreshTokenRepo = $refreshTokenRepo;
    }

    public function create($payload = []) {
        DB::beginTransaction();
        try {
            $refreshToken = $this->refreshTokenRepo->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $th) {
            DB::rollBack();
            return false;
        }

    }
}
