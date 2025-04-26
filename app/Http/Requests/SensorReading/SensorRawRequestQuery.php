<?php

namespace App\Http\Requests\SensorReading;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="SensorRawRequestQuery",
 *     @OA\Property(property="from", type="string", format="date-time", description="Start date for readings"),
 *     @OA\Property(property="to", type="string", format="date-time", description="End date for readings"),
 *     @OA\Property(property="page", type="integer", minimum=1, description="Page number"),
 *     @OA\Property(property="per_page", type="integer", minimum=1, maximum=100, description="Number of items per page"),
 *     @OA\Property(property="sort_by", type="string", enum={"value", "created_at"}, description="Field to sort by"),
 *     @OA\Property(property="sort_dir", type="string", enum={"asc", "desc"}, description="Sort direction")
 * )
 */
class SensorRawRequestQuery extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date', 'before_or_equal:now'],
            'to' => ['nullable', 'date', 'after_or_equal:from', 'before_or_equal:now'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'string', Rule::in(['value', 'created_at'])],
            'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
