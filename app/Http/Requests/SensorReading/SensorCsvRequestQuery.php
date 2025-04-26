<?php

namespace App\Http\Requests\SensorReading;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="SensorCsvRequestQuery",
 *     @OA\Property(property="from", type="string", format="date-time", description="Start date for readings"),
 *     @OA\Property(property="to", type="string", format="date-time", description="End date for readings")
 * )
 */
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
