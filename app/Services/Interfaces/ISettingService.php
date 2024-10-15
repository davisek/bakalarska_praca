<?php
namespace App\Services\Interfaces;

use App\Data\SettingData;

interface ISettingService
{
    public function update(SettingData $settingData);
}
