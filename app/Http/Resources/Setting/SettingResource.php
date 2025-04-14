<?php

namespace App\Http\Resources\Setting;

use App\Http\Resources\Sensor\SensorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email_notification_allowed' => $this->email_notification_allowed,
            'threshold' => $this->threshold,
            'cooldown' => $this->cooldown,
            'min_unit_diff' => $this->min_unit_diff,
            'sensor' => SensorResource::make($this->sensor)
        ];
    }
}
