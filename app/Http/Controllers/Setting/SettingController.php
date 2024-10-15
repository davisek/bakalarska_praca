<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ISettingService;

class SettingController extends Controller
{
    protected readonly ISettingService $settingService;

    public function __construct(ISettingService $settingService)
    {
        $this->settingService = $settingService;
    }
}
