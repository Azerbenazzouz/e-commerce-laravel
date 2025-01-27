<?php
namespace App\Service\Impl;

use App\Repositories\RoleRepository;
use Illuminate\Support\Str;

class RoleService extends BaseService {
    
    protected $roleRepo;
    protected $roleEntity;
    protected $payload;

    public function __construct(
        RoleRepository $roleRepo
    ) {
        parent::__construct($roleRepo);        
    }

    protected function requestPayload(): array {
        return ['name', 'publish'];
    }

    protected function processPayload() {
        return $this
            ->generateSlug($this->payload['name'])
            ->generateSomething();
    }

    protected function generateSlug($name) {
        $this->payload['slug'] = Str::slug($this->payload['name']);
        return $this;
    }

    protected function generateSomething() {
        // do something
        return $this;
    }
}
