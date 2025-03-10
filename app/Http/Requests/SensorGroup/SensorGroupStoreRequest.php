<?php

namespace App\Http\Requests\SensorGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SensorGroupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_name' => ['required', 'string', Rule::unique('sensor_groups', 'group_name')],
            'group_value' => ['required', 'string', Rule::unique('sensor_groups', 'group_value')],
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
