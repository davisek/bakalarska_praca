<?php

namespace App\Providers;

use App\Models\Measurement;
use App\Models\User;
use App\Observers\MeasurementObserver;
use App\Observers\UserObserver;
use App\Services\AuthService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ILogService;
use App\Services\Interfaces\ISensorGroupService;
use App\Services\Interfaces\ISensorReadingService;
use App\Services\Interfaces\ISensorService;
use App\Services\Interfaces\ISettingService;
use App\Services\Interfaces\IUserService;
use App\Services\LogService;
use App\Services\SensorGroupService;
use App\Services\SensorReadingService;
use App\Services\SensorService;
use App\Services\SettingService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ISettingService::class, SettingService::class);
        $this->app->bind(ISensorReadingService::class, SensorReadingService::class);
        $this->app->bind(ISensorService::class, SensorService::class);
        $this->app->bind(ISensorGroupService::class, SensorGroupService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(ILogService::class, LogService::class);
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
        Measurement::observe(MeasurementObserver::class);
    }
}
