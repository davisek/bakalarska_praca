<?php

namespace App\Http\Requests\SensorReading;

use App\Enums\SensorReading\SensorEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SensorCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sensor_name' => ['required', 'string'],
            'value' => ['required', 'numeric'],
        ];
    }
}
