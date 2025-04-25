<?php

namespace App\Http\Requests\SensorReading;

use Illuminate\Foundation\Http\FormRequest;

class SensorCsvRequestQuery extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date', 'before_or_equal:now'],
            'to' => ['nullable', 'date', 'after_or_equal:from', 'before_or_equal:now']
        ];
    }
}
