<?php
namespace App\Services;

use App\Models\Setting;
use App\Services\Interfaces\IUserService;

class UserService implements IUserService
{
    public function get($userId)
    {
        $setting = Setting::where('user_id', $userId)->first();

        return $setting;
    }
}
