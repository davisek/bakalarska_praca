<?php

namespace App\Observers;

use App\Mail\BinaryNotificationEmail;
use App\Mail\NotificationEmail;
use App\Models\Measurement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MeasurementObserver
{
    public function created(Measurement $measurement): void
    {
        $sensor = $measurement->sensor;

        $now = Carbon::now();

        $settings = $sensor->notificationSettings()
            ->where('email_notification_allowed', true)
            ->with('user')
            ->get();

        foreach ($settings as $setting) {
            $previous = $sensor->measurements()
                ->where('id', '<', $measurement->id)
                ->latest('created_at')
                ->first();

            if (!$previous) {
                continue;
            }

            if ($setting->cooldown && $setting->last_notification_at) {
                $nextAllowed = Carbon::parse($setting->last_notification_at)->addHours($setting->cooldown);
                if ($now->lt($nextAllowed)) {
                    continue;
                }
            }

            if ($sensor->is_output_binary) {
                if ($measurement->value == $previous->value) {
                    continue;
                }

                Mail::to($setting->user->email)->send(new BinaryNotificationEmail($measurement, $setting->user));
            } else {
                if ($previous->value == 0) {
                    continue;
                }

                $absoluteDiff = abs($measurement->value - $previous->value);
                $percentChange = abs(($measurement->value - $previous->value) / $previous->value * 100);

                if (!is_null($setting->threshold) && $percentChange < $setting->threshold) {
                    continue;
                }

                if (!is_null($setting->min_unit_diff) && $absoluteDiff < $setting->min_unit_diff) {
                    continue;
                }

                Mail::to($setting->user->email)->send(new NotificationEmail($measurement, $setting->user, $percentChange));
            }

            $setting->last_notification_at = $now;
            $setting->save();
        }
    }
}
