<?php

namespace App\Http\Requests\Permission;

use App\Http\Requests\BaseRequest;

class DeleteMultipleRequest extends BaseRequest {

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array|exists:permissions,id',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => explode(',', $this->route('permissions')) // id=1,2,3
        ]);
    }

}
