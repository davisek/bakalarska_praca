<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ForgotPasswordRequest",
 *     required={"email"},
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, description="User's email address")
 * )
 */
class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'max:255']
        ];
    }
}
