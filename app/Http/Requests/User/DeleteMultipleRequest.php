<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Repositories\UserRepository;

class DeleteMultipleRequest extends BaseRequest {
    
    private $userRepository;

    public function __construct() {
        $this->userRepository = app(UserRepository::class);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array|exists:users,id',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'id' => explode(',', $this->route('users'))
        ]);
    }

}
