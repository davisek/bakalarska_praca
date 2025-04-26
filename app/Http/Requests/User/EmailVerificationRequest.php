<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="EmailVerificationRequest",
 *     required={"verification_code"},
 *     @OA\Property(property="verification_code", type="string", minLength=5, maxLength=5, description="Email verification code")
 * )
 */
class EmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'verification_code' => ['required', 'string', 'size:5']
        ];
    }
}
