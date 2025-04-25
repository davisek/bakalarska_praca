<?php

namespace App\Http\Requests\Sensor;

use App\Enums\Sensor\ColorClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SensorStoreRequest extends FormRequest
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
            'color_class' => ['required', 'max:50', Rule::in(ColorClass::cases())],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sensor_group_id' => ['required', 'numeric', Rule::exists('sensor_groups', 'id')],
        ];
    }
}
