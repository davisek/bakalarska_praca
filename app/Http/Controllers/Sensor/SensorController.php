<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sensor\SensorStoreRequest;
use App\Http\Requests\Sensor\SensorUpdateRequest;
use App\Http\Resources\Sensor\SensorResource;
use App\Models\NotificationSetting;
use App\Models\Sensor;
use App\Models\User;
use App\Services\Interfaces\ISensorReadingService;
use Illuminate\Support\Facades\Storage;

class SensorController extends Controller
{
    protected readonly ISensorReadingService $sensorReadingService;

    public function __construct(ISensorReadingService $sensorReadingService)
    {
        $this->sensorReadingService = $sensorReadingService;
    }

    public function show(int $sensorId)
    {
        $sensor = Sensor::findOrFail($sensorId);

        return SensorResource::make($sensor);
    }

    public function store(SensorStoreRequest $request)
    {
        $data = $request->validated();

        $sensor = new Sensor();
        $sensor->sensor_name = $data['sensor_name'];
        $sensor->type = $data['type'];
        $sensor->display_name = $data['display_name'] ?? $data['sensor_name'];
        $sensor->unit_of_measurement = $data['unit_of_measurement'];
        $sensor->color_class = $data['color_class'];
        $sensor->sensor_group_id = $data['sensor_group_id'];

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

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
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
        $sensor->update([
            'sensor_name' => $data['sensor_name'],
            'type' => $data['type'],
            'display_name' => $data['display_name'],
            'unit_of_measurement' => $data['unit_of_measurement'],
        ]);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    public function destroy(int $sensorId)
    {
        Sensor::findOrFail($sensorId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}
