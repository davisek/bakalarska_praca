<?php

namespace App\Http\Requests\User;

use App\Enums\User\LocaleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     required={"name", "surname", "email", "password", "password_confirmation", "locale"},
 *     @OA\Property(property="name", type="string", maxLength=50, description="User's first name"),
 *     @OA\Property(property="surname", type="string", maxLength=50, description="User's last name"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address"),
 *     @OA\Property(property="password", type="string", format="password", minLength=8, maxLength=20, description="User's password"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 *     @OA\Property(property="locale", type="string", description="User's preferred locale")
 * )
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
            'locale' => ['required', 'string', Rule::in(LocaleEnum::cases())]
        ];
    }
}
