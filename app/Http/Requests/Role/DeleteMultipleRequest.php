<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use App\Repositories\RoleRepository;

class DeleteMultipleRequest extends BaseRequest {
    
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
            'ids' => 'required|array',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $ids = $this->input('ids');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    // dd($id);
                    if (!is_numeric($id)) {
                        $validator->errors()->add('role', 'Role id must be numeric');
                    }
                    $role = $this->roleRepository->findByld($id);
                    if (!$role) {
                        $validator->errors()->add('role', 'Role not found with id: '.$id);
                    }
                }
            }
        });
    }
}
