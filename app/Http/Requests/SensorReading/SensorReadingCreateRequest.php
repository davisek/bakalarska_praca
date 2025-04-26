<?php

namespace App\Http\Requests\SensorReading;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="SensorReadingCreateRequest",
 *     required={"sensor_name", "value"},
 *     @OA\Property(property="sensor_name", type="string", maxLength=50, description="Name of the sensor"),
 *     @OA\Property(property="value", type="number", format="float", description="Reading value")
 * )
 */
class SensorReadingCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sensor_name' => ['required', 'string', 'max:50'],
            'value' => ['required', 'numeric'],
        ];
    }
}
