<?php

namespace App\Http\Resources\SensorReading;

use App\Enums\Setting\SymbolEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

class SensorLinksResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'group_name' => $this->group_name,
            'group_value' => $this->group_value,
            'sensors' => $this->whenLoaded('sensors', function () {
                return $this->sensors->map(function ($sensor) {
                    return [
                        'type' => $sensor->type,
                        'display_name' => $sensor->display_name,
                    ];
                });
            }),
        ];
    }
}
