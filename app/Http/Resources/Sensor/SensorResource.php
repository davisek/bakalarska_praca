<?php

namespace App\Http\Resources\Sensor;

use App\Enums\Setting\SymbolEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

class SensorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sensor_name' => $this->sensor_name,
            'type' => $this->type,
            'display_name' => $this->display_name,
            'image_path' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'icon_path' => $this->icon_path ? asset('storage/' . $this->icon_path) : null,
        ];
    }
}
