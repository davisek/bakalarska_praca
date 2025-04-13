<?php

namespace App\Http\Requests\Setting;

use App\Models\NotificationSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*.id' => ['required', 'integer', 'exists:notification_settings,id'],
            'settings.*.email_notification_allowed' => ['required', 'boolean'],
            'settings.*.threshold' => [
                'present',
                function ($attribute, $value, $fail) {
                    preg_match('/settings\.(\d+)\.threshold/', $attribute, $matches);
                    $index = $matches[1] ?? null;

                    if ($index !== null) {
                        $settingId = request("settings.$index.id");
                        $setting = NotificationSetting::with('sensor')->find($settingId);

                        if ($setting && $setting->sensor && !$setting->sensor->is_output_binary) {
                            if (is_null($value)) {
                                $fail(trans('validation.custom.threshold.required_when_not_binary'));
                            } elseif (!is_numeric($value) || $value < 1 || $value > 100) {
                                $fail(trans('validation.custom.threshold.range'));
                            }
                        }
                    }
                }
            ],
            'settings.*.cooldown' => ['required', 'integer'],
            'settings.*.min_unit_diff' => ['nullable', 'numeric', 'max:99.99'],
        ];
    }
}
