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
            'temperature_notification' => (bool)$this->temperature_notification,
            'humidity_notification' => (bool)$this->humidity_notification,
            'pressure_notification' => (bool)$this->pressure_notification,
            'in_celsius' => (bool)$this->in_celsius,
        ];
    }
}
