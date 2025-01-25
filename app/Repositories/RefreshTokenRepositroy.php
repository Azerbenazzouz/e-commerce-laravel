<?php
namespace App\Repositories;

use App\Models\RefreshToken;

class RefreshTokenRepositroy extends BaseRepositroy{
    private $model;

    public function __construct(RefreshToken $model) {
        parent::__construct($model);
    }

}
