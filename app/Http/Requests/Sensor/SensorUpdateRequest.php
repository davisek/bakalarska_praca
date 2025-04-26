<?php

namespace App\Http\Requests\Sensor;

use App\Enums\Sensor\ColorClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="SensorUpdateRequest",
 *     required={"sensor_name", "type", "display_name", "is_output_binary", "color_class", "sensor_group_id"},
 *     @OA\Property(property="sensor_name", type="string", maxLength=50, description="Name of the sensor"),
 *     @OA\Property(property="type", type="string", maxLength=50, description="Type identifier of the sensor"),
 *     @OA\Property(property="display_name", type="string", maxLength=50, description="Display name of the sensor"),
 *     @OA\Property(property="unit_of_measurement", type="string", maxLength=10, nullable=true, description="Unit of measurement for sensor readings"),
 *     @OA\Property(property="is_output_binary", type="boolean", description="Whether sensor output is binary"),
 *     @OA\Property(property="color_class", type="string", description="CSS color class for the sensor"),
 *     @OA\Property(property="image", type="string", format="binary", description="Sensor image file"),
 *     @OA\Property(property="icon", type="string", format="binary", description="Sensor icon file"),
 *     @OA\Property(property="sensor_group_id", type="integer", description="ID of the sensor group")
 * )
 */
class SensorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sensorId = $this->route('sensorId');

        return [
            'sensor_name' => ['required', 'string', 'max:50'],
            'type' => ['required', 'string', 'max:50', Rule::unique('sensors', 'type')->ignore($sensorId)],
            'display_name' => ['required', 'string', 'max:50', Rule::unique('sensors', 'display_name')->ignore($sensorId)],
            'unit_of_measurement' => ['nullable', 'string', 'max:10'],
            'is_output_binary' => ['required', 'boolean'],
            'color_class' => ['required', 'string', Rule::in(ColorClass::cases())],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sensor_group_id' => ['required', 'numeric', Rule::exists('sensor_groups', 'id')],
        ];
    }
}
