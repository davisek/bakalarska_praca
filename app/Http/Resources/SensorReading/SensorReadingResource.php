<?php

namespace App\Http\Resources\SensorReading;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SensorReadingResource",
 *     @OA\Property(property="value", type="number", format="float", description="Sensor reading value"),
 *     @OA\Property(property="symbol", type="string", description="Unit of measurement symbol"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when reading was recorded")
 * )
 */
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
