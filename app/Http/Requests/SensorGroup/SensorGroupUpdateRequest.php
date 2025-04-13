<?php

namespace App\Http\Requests\SensorGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SensorGroupUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sensorGroupId = $this->route('sensorGroupId');

        return [
            'group_name' => ['required', 'string', 'max:50', Rule::unique('sensor_groups', 'group_name')->ignore($sensorGroupId)],
            'group_value' => ['required', 'string', 'max:50', Rule::unique('sensor_groups', 'group_value')->ignore($sensorGroupId)],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
