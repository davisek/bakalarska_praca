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

        foreach ($settingData->settings as $setting) {
            $user->notificationSettings()
                ->where('id', $setting['id'])
                ->update($setting);
        }
    }
}
