<?php

namespace App\Http\Resources\SensorReading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->resource['value'],
            'symbol' => $this->resource['symbol'],
            'recorded_at' => $this->resource['created_at']->toDateTimeString(),
        ];
    }
}
