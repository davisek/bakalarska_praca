<?php

namespace App\Http\Requests\SensorReading;

use Illuminate\Foundation\Http\FormRequest;

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
