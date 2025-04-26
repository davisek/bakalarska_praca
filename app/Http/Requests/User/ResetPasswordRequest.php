<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ResetPasswordRequest",
 *     required={"email", "code", "password", "password_confirmation"},
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address"),
 *     @OA\Property(property="code", type="string", minLength=5, maxLength=5, description="Password reset verification code"),
 *     @OA\Property(property="password", type="string", format="password", minLength=8, maxLength=20, description="New password"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", description="New password confirmation")
 * )
 */
class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'code' => ['required', 'string', 'size:5'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
        ];
    }
}
