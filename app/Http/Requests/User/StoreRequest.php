<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'name' => 'required',
            'password' => 'required|min:6|max:24',
            'email' => 'required|email|unique:users,email',
            'birthday' => 'required|date|before:today',
            'publish' => 'gt:0',
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id', // Validate each item in the array (roles ID exists in the roles table)
        ];
    }
}
