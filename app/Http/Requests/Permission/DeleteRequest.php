<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;
use App\Repositories\PermissionRepository;

class DeleteRequest extends BaseRequest {

    private $permissionRepository;

    public function __construct() {
        $this->permissionRepository = app(PermissionRepository::class);
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
            $permission = $this->permissionRepository->findByld($this->route('permission'));
            if (!$permission) {
                $validator->errors()->add('permission', 'Permission not found');
            }
        });
    }
}
