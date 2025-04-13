<?php

namespace App\Http\Requests\User;

use App\Enums\User\LocaleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
