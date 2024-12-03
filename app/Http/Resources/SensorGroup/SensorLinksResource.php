<?php

namespace App\Http\Resources\SensorGroup;

use App\Http\Resources\Sensor\SensorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorLinksResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'group_value' => $this->group_value,
            'image_path' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'sensors' => $this->whenLoaded('sensors', function () {
                return $this->sensors->map(function ($sensor) {
                    return SensorResource::make($sensor);
                });
            }),
        ];
    }
}
