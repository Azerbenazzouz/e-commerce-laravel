<?php

namespace App\Providers;

use App\Service\Impl\RoleService;
use App\Service\Impl\UserService;
use App\Service\Interfaces\RoleServiceInterface;
use App\Service\Interfaces\UserServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app
            ->bind(RoleServiceInterface::class, RoleService::class);
        $this->app
            ->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
