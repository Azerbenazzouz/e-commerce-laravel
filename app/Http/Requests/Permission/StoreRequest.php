<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required|regex:/^[a-z]+:[a-zA-Z]+$/|unique:permissions',
            'publish' => 'required|gt:0'
        ];
    }
}
