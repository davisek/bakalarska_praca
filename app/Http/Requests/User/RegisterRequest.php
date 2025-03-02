<?php

namespace App\Http\Requests\User;

use App\Enums\Setting\SymbolEnum;
use App\Enums\User\LocaleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'surname' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'locale' => ['required', 'string', Rule::in(LocaleEnum::cases())],
        ];
    }
}
