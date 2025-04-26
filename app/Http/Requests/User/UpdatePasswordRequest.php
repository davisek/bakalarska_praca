<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdatePasswordRequest",
 *     required={"current_password", "password", "password_confirmation"},
 *     @OA\Property(property="current_password", type="string", format="password", minLength=8, maxLength=20, description="Current password"),
 *     @OA\Property(property="password", type="string", format="password", minLength=8, maxLength=20, description="New password"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", description="New password confirmation")
 * )
 */
class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'min:8', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
        ];
    }
}
