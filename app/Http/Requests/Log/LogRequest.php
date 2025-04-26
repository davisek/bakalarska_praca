<?php

namespace App\Http\Requests\Log;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LogRequest",
 *     required={"message"},
 *     @OA\Property(property="message", type="string", description="Log message"),
 * )
 */
class LogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
        ];
    }
}
