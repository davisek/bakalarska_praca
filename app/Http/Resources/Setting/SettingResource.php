<?php

namespace App\Http\Resources\Setting;

use App\Http\Resources\Sensor\SensorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email_notification_allowed' => $this->email_notification_allowed,
            'sensor' => SensorResource::make($this->sensor)
        ];
    }
}
