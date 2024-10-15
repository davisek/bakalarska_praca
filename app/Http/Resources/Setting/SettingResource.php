<?php

namespace App\Http\Resources\Setting;

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
            'temperature_notification' => $this->temperature_notification,
            'humidity_notification' => $this->humidity_notification,
            'pressure_notification' => $this->pressure_notification,
            'in_celsius' => $this->in_celsius,
        ];
    }
}
