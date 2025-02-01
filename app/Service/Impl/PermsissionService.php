<?php
namespace App\Service\Impl;

use App\Repositories\PermissionRepository;
use App\Service\Interfaces\PermissionServiceInterface;

class PermsissionService extends BaseService implements PermissionServiceInterface{
    
    protected $permsissionRepo;
    protected $payload;

    public function __construct(
        PermissionRepository $permsissionRepo
    ) {
        parent::__construct($permsissionRepo);        
    }

    protected function requestPayload(): array {
        return ['name', 'publish'];
    }

    protected function getSearchFieald(): array {
        return ['name'];
    }

    protected function getPerpage() : int {
        return 20;
    }

    protected function getSimpleFilter() : array {
        return ['publish'];
    }

    protected function getComplexFilter(): array{
        return ['id'];
    }

    protected function getDateFilter(): array {
        return ['created_at'];
    }
}
