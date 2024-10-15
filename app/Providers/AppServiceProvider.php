<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Services\AuthService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ISensorReadingService;
use App\Services\Interfaces\ISettingService;
use App\Services\Interfaces\IUserService;
use App\Services\SensorReadingService;
use App\Services\SettingService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ISettingService::class, SettingService::class);
        $this->app->bind(ISensorReadingService::class, SensorReadingService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
