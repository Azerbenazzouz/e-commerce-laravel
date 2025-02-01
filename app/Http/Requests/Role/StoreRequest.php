<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name',
            'publish' => 'required|gt:0',
            'permissions' => 'required|array', // Must be an array
            'permissions.*' => 'required|exists:permissions,id', // Validate each item in the array (roles ID exists in the roles table)
        ];
    }
}
