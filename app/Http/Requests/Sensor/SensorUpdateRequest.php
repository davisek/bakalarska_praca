<?php

namespace App\Http\Requests\Sensor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'sensor_name' => ['required', 'string'],
            'type' => ['required', 'string', Rule::unique('sensors', 'type')->ignore($sensorId)],
            'display_name' => ['required', 'string'],
            'unit_of_measurement' => ['required', 'string'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'icon' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sensor_group_id' => ['required', 'numeric', Rule::exists('sensor_groups', 'id')],
        ];
    }
}
