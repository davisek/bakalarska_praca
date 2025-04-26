<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UserSearchRequestQuery",
 *     @OA\Property(property="page", type="integer", minimum=1, description="Page number"),
 *     @OA\Property(property="per_page", type="integer", minimum=1, maximum=100, description="Number of items per page"),
 *     @OA\Property(property="search", type="string", maxLength=255, description="Search term"),
 *     @OA\Property(property="sort_by", type="string", enum={"name", "surname", "email", "email_verified_at", "is_admin", "created_at"}, description="Field to sort by"),
 *     @OA\Property(property="sort_dir", type="string", enum={"asc", "desc"}, description="Sort direction")
 * )
 */
class UserSearchRequestQuery extends FormRequest
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
            'sort_by' => ['nullable', 'string', Rule::in(['name', 'surname', 'email', 'email_verified_at', 'is_admin', 'created_at'])],
            'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
