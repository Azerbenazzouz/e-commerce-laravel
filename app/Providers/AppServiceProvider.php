<?php

namespace App\Providers;

use App\Service\Impl\RoleService;
use App\Service\Interfaces\RoleServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
