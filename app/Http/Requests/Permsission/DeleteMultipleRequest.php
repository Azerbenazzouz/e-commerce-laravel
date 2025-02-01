<?php

namespace App\Http\Requests\Permsission;

use App\Http\Requests\BaseRequest;
use App\Repositories\PermsissionRepository;

class DeleteMultipleRequest extends BaseRequest {
    
    // private $permsissionRepository;

    // public function __construct() {
    //     $this->permsissionRepository = app(PermsissionRepository::class);
    // }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array|exists:permsissions,id',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => explode(',', $this->route('permsissions')) // id=1,2,3
        ]);
    }

}
