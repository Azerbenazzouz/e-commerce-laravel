<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class CreateModuleRequest extends BaseRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'model' => 'required|regex:/^[a-z]+$/', // example: 'user' 
            'publish' => 'required|gt:0' 
        ];
    }
}
