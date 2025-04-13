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
use App\Services\Interfaces\ISensorService;
use Illuminate\Support\Facades\Storage;

class SensorController extends Controller
{
    protected readonly ISensorService $sensorService;

    public function __construct(ISensorService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    public function show(int $sensorId)
    {
        $sensor = Sensor::findOrFail($sensorId);

        return SensorResource::make($sensor);
    }

    public function create(SensorStoreRequest $request)
    {
        $this->sensorService->create($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }

    public function update(int $sensorId, SensorUpdateRequest $request)
    {
        $this->sensorService->update($sensorId, $request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    public function delete(int $sensorId)
    {
        Sensor::findOrFail($sensorId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}
