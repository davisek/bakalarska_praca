<?php
namespace App\Services;

use App\Http\Requests\Sensor\SensorStoreRequest;
use App\Http\Requests\Sensor\SensorUpdateRequest;
use App\Models\NotificationSetting;
use App\Models\Sensor;
use App\Models\User;
use App\Services\Interfaces\ISensorService;
use Illuminate\Support\Facades\Storage;

class SensorService implements ISensorService
{
    public function create(SensorStoreRequest $request)
    {
        $data = $request->validated();

        $sensor = Sensor::create([$data]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/sensors', 'public');
            $sensor->image_path = $path;
        }

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('images/sensors', 'public');
            $sensor->icon_path = $path;
        }

        $sensor->save();

        $users = User::all();
        foreach ($users as $user) {
            NotificationSetting::create([
                'user_id' => $user->id,
                'sensor_id' => $sensor->id,
                'email_notification_allowed' => false,
            ]);
        }
    }

    public function update(int $sensorId, SensorUpdateRequest $request)
    {
        $data = $request->validated();

        $sensor = Sensor::findOrFail($sensorId);

        if ($request->hasFile('image')) {
            if ($sensor->image_path) {
                Storage::disk('public')->delete($sensor->image_path);
            }

            $path = $request->file('image')->store('images/sensors', 'public');
            $sensor->image_path = $path;
        }

        if ($request->hasFile('icon')) {
            if ($sensor->icon_path) {
                Storage::disk('public')->delete($sensor->icon_path);
            }

            $path = $request->file('icon')->store('images/sensors', 'public');
            $sensor->icon_path = $path;
        }

        $sensor->save();
        $sensor->update($data);
    }
}
