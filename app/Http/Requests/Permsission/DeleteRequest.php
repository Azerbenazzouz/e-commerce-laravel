<?php

namespace App\Http\Requests\Permsission;

use App\Http\Requests\BaseRequest;
use App\Repositories\PermsissionRepository;

class DeleteRequest extends BaseRequest {

    private $permsissionRepository;

    public function __construct() {
        $this->permsissionRepository = app(PermsissionRepository::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array {
        return [];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $permsission = $this->permsissionRepository->findByld($this->route('permsission'));
            if (!$permsission) {
                $validator->errors()->add('permsission', 'Permsission not found');
            }
        });
    }
}
