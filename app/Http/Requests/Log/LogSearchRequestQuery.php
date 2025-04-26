<?php

namespace App\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="LogSearchRequest",
 *     @OA\Property(property="page", type="integer", minimum=1, description="Page number"),
 *     @OA\Property(property="per_page", type="integer", minimum=1, maximum=100, description="Number of items per page"),
 *     @OA\Property(property="search", type="string", description="Search term"),
 *     @OA\Property(property="sort_by", type="string", enum={"created_at"}, description="Field to sort by"),
 *     @OA\Property(property="sort_dir", type="string", enum={"asc", "desc"}, description="Sort direction")
 * )
 */
class LogSearchRequestQuery extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'string', Rule::in(['created_at'])],
            'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
