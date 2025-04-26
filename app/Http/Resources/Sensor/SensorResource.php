<?php

namespace App\Http\Resources\Sensor;

use App\Enums\Setting\SymbolEnum;
use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @OA\Schema(
 *     schema="SensorResource",
 *     @OA\Property(property="id", type="integer", description="Sensor ID"),
 *     @OA\Property(property="sensor_name", type="string", description="Name of the sensor"),
 *     @OA\Property(property="type", type="string", description="Type identifier of the sensor"),
 *     @OA\Property(property="display_name", type="string", description="Display name of the sensor"),
 *     @OA\Property(property="unit_of_measurement", type="string", nullable=true, description="Unit of measurement for sensor readings"),
 *     @OA\Property(property="is_output_binary", type="boolean", description="Whether sensor output is binary"),
 *     @OA\Property(property="image_path", type="string", nullable=true, description="Path to sensor image"),
 *     @OA\Property(property="icon_path", type="string", nullable=true, description="Path to sensor icon"),
 *     @OA\Property(
 *         property="color_class",
 *         nullable=true,
 *         ref="#/components/schemas/EnumResource"
 *     ),
 *     @OA\Property(property="group_name", type="string", description="Name of the sensor group")
 * )
 */
class SensorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sensor_name' => $this->sensor_name,
            'type' => $this->type,
            'display_name' => $this->display_name,
            'unit_of_measurement' => $this->unit_of_measurement,
            'is_output_binary' => $this->is_output_binary,
            'image_path' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'icon_path' => $this->icon_path ? asset('storage/' . $this->icon_path) : null,
            'color_class' => $this->color_class ? EnumResource::make($this->color_class) : null,
            'group_name' => $this->sensorGroup->group_name,
        ];
    }
}
