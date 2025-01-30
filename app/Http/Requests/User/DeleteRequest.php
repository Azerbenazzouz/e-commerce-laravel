<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Repositories\UserRepository;

class DeleteRequest extends BaseRequest {

    private $userRepository;

    public function __construct() {
        $this->userRepository = app(UserRepository::class);
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
            $user = $this->userRepository->findByld($this->route('user'));
            if (!$user) {
                $validator->errors()->add('user', 'User not found');
            }
        });
    }
}
