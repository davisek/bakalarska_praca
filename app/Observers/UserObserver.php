<?php

namespace App\Observers;

use App\Models\NotificationSetting;
use App\Models\Sensor;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            NotificationSetting::create([
                'user_id' => $user->id,
                'sensor_id' => $sensor->id,
                'email_notification_allowed' => false,
            ]);
        }
    }
}
