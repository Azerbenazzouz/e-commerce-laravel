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
            'ids' => 'required|array',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            $ids = $this->input('ids');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    if (!is_numeric($id)) {
                        $validator->errors()->add('user', 'User id must be numeric');
                    }
                    $user = $this->userRepository->findByld($id);
                    if (!$user) {
                        $validator->errors()->add('user', 'User not found with id: '.$id);
                    }
                }
            }
        });
    }
}
