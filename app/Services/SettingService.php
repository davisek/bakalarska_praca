<?php
namespace App\Services;

use App\Models\Setting;
use App\Services\Interfaces\ISettingService;

class SettingService implements ISettingService
{
    public function show($userId)
    {
        $setting = Setting::where('user_id', $userId)->first();

        return $setting;
    }
}
