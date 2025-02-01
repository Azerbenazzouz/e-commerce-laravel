<?php

namespace App\Http\Requests\Permsission;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest {
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [
            'id' => 'required|exists:permsissions,id',
            'name' => 'required',
            'publish' => 'gt:0'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => $this->route('permsission')
        ]);
    }
}
