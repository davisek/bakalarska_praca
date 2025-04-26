<?php

namespace App\Http\Resources\SensorGroup;

use App\Http\Resources\Sensor\SensorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SensorLinksResource",
 *     @OA\Property(property="id", type="integer", description="Sensor group ID"),
 *     @OA\Property(property="group_name", type="string", description="Name of the sensor group"),
 *     @OA\Property(property="group_value", type="string", description="Value identifier of the sensor group"),
 *     @OA\Property(property="image_path", type="string", nullable=true, description="Path to sensor group image"),
 *     @OA\Property(
 *         property="sensors",
 *         type="array",
 *         description="Sensors in this group",
 *         @OA\Items(ref="#/components/schemas/SensorResource")
 *     )
 * )
 */
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
