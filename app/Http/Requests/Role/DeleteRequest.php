<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Repositories\RoleRepository;

class DeleteRequest extends BaseRequest {

    private $roleRepository;

    public function __construct() {
        $this->roleRepository = app(RoleRepository::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $role = $this->roleRepository->findByld($this->route('role'));
            if (!$role) {
                $validator->errors()->add('role', 'Role not found');
            }
        });
    }
}
