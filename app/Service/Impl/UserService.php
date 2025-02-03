<?php
namespace App\Service\Impl;

use App\Repositories\UserRepository;
use App\Service\Interfaces\UserServiceInterface;
use Carbon\Carbon;
use App\Service\Impl\ImageUploadService;
use Illuminate\Http\Request;
class UserService extends BaseService implements UserServiceInterface {
    
    protected $userRepo;
    protected $payload;
    protected $imageUploadService;

    public function __construct(
        UserRepository $userRepo,
        ImageUploadService $imageUploadService
    ) {
        $this->imageUploadService = $imageUploadService;
        parent::__construct($userRepo);
    }

    protected function getSearchField(): array {
        return ['name', 'email'];
    }

    protected function getPerpage() : int {
        return 20;
    }

    protected function requestPayload(): array {
        return ['name', 'email', 'password' ,'publish', 'birthday', 'roles'];
    }

    protected function getSimpleFilter() : array {
        return ['name', 'email'];
    }

    protected function getComplexFilter(): array{
        return ['id', 'age'];
    }

    protected function getDateFilter(): array {
        return ['created_at', 'birthday'];
    }

    protected function processPayload(?Request $request = null) {
        if (!$request) {
            return $this;
        }
        
        return $this
            ->calculateAgeFromBirthday()
            ->uploadAvatar($request);
    }

    protected function calculateAgeFromBirthday() {
        if(isset($this->payload['birthday'])) {
            $this->payload['age'] = Carbon::parse($this->payload['birthday'])->age;
        }
        return $this;
    }
    
    protected function uploadAvatar(Request $request) {
        $argument = [
            'files' => $request->file('image'),
            'folder' => 'avatar',
            'pipelineKey' => 'default',
            'overrideOptions' => [
                'optimize' => [
                    'quality' => 80,
                ]
            ]
        ];
        $this->payload['avatar'] = $this->imageUploadService->upload(...$argument);
        
        return $this;
    }
    protected function getManyToManyRelationship() : array {
        return ['roles'];
    }

}
