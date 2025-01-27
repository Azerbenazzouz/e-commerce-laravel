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
            'name' => 'required',
            'publish' => 'gt:0',
        ];
    }
}
