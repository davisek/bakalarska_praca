<?php

namespace App\Http\Requests\User;

use App\Enums\User\LocaleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     required={"name", "surname", "email", "locale", "dark_mode"},
 *     @OA\Property(property="name", type="string", maxLength=50, description="User's first name"),
 *     @OA\Property(property="surname", type="string", maxLength=50, description="User's last name"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address"),
 *     @OA\Property(property="locale", type="string", description="User's preferred locale"),
 *     @OA\Property(property="dark_mode", type="boolean", description="Whether dark mode is enabled")
 * )
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'locale' => ['required', 'string', Rule::in(LocaleEnum::cases())],
            'dark_mode' => ['required', 'boolean'],
        ];
    }
}
