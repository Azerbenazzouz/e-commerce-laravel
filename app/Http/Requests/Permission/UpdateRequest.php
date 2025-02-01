<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'regex:/^[a-z]+:[a-zA-Z]+$/|unique:permissions|nullable',
            'publish' => 'gt:0|nullable'
        ];
    }
}
