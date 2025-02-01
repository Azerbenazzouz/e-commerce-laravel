<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Repositories\RoleRepository;

class UpdateRequest extends BaseRequest {

    private $roleRepository;

    public function __construct() {
        $this->roleRepository = app(RoleRepository::class);

    }
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255|min:3',
            'publish' => 'gt:0|nullable',
            'permissions' => 'array|nullable', // Must be an array
            'permissions.*' => 'required|exists:permissions,id', // Validate each item in the array (roles ID exists in the roles table)
        ];
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
