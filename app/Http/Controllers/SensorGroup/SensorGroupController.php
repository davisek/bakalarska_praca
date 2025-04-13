<?php

namespace App\Http\Controllers\SensorGroup;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorGroup\SensorGroupStoreRequest;
use App\Http\Requests\SensorGroup\SensorGroupUpdateRequest;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\SensorGroup\SensorLinksResource;
use App\Models\Sensor;
use App\Models\SensorGroup;
use App\Services\Interfaces\ISensorGroupService;
use Illuminate\Support\Facades\Storage;

class SensorGroupController extends Controller
{
    protected readonly ISensorGroupService $sensorGroupService;

    public function __construct(ISensorGroupService $sensorGroupService)
    {
        $this->sensorGroupService = $sensorGroupService;
    }

    public function index()
    {
        $data = SensorGroup::with('sensors')->get();

        return SensorLinksResource::collection($data);
    }

    public function show(int $sensorGroupId)
    {
        $data = SensorGroup::with('sensors')->findOrFail($sensorGroupId);

        return SensorLinksResource::make($data);
    }

    public function create(SensorGroupStoreRequest $request)
    {
        $this->sensorGroupService->create($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }

    public function update(int $sensorGroupId, SensorGroupUpdateRequest $request)
    {
        $this->sensorGroupService->update($sensorGroupId, $request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    public function delete(int $sensorGroupId)
    {
        SensorGroup::findOrFail($sensorGroupId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}
