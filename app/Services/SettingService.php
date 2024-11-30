<?php
namespace App\Services;

use App\Data\SettingData;
use App\Models\NotificationSetting;
use App\Services\Interfaces\ISettingService;
use Illuminate\Support\Facades\Auth;

class SettingService implements ISettingService
{
    public function update(SettingData $settingData)
    {
        $user = Auth::user();

        $user->setting()->update([
            'temperature_notification' => $settingData->temperature_notification,
            'humidity_notification' => $settingData->humidity_notification,
            'pressure_notification' => $settingData->pressure_notification,
            'in_celsius' => $settingData->in_celsius,
        ]);
    }
}
