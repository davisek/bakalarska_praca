<?php

namespace App\Http\Resources\SensorReading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorReadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->value,
            'symbol' => $this->sensor->unit_of_measurement,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
