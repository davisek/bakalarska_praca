<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address"),
 *     @OA\Property(property="password", type="string", format="password", minLength=6, maxLength=20, description="User's password")
 * )
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:20']
        ];
    }
}
