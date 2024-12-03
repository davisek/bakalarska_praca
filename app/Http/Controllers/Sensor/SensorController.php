<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sensor\SensorUpdateRequest;
use App\Http\Resources\Sensor\SensorResource;
use App\Models\Sensor;
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

    public function create()
    {

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
            'message' => 'Sensor successfully updated.',
        ]);
    }

    public function destroy()
    {

    }
}
