<?php

namespace App\Http\Controllers\SensorGroup;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorGroup\SensorGroupStoreRequest;
use App\Http\Requests\SensorGroup\SensorGroupUpdateRequest;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\SensorGroup\SensorLinksResource;
use App\Models\Sensor;
use App\Models\SensorGroup;
use Illuminate\Support\Facades\Storage;

class SensorGroupController extends Controller
{
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

    public function store(SensorGroupStoreRequest $request)
    {
        $data = $request->validated();

        $sensorGroup = SensorGroup::create([
            'group_name' => $data['group_name'],
            'group_value' => $data['group_value']
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/sensors', 'public');
            $sensorGroup->image_path = $path;
            $sensorGroup->save();
        }

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }

    public function update(int $sensorGroupId, SensorGroupUpdateRequest $request)
    {
        $data = $request->validated();
        $sensorGroup = SensorGroup::findOrFail($sensorGroupId);

        if ($request->hasFile('image')) {
            if ($sensorGroup->image_path) {
                Storage::disk('public')->delete($sensorGroup->image_path);
            }

            $path = $request->file('image')->store('images/sensors', 'public');
            $sensorGroup->image_path = $path;
        }

        $sensorGroup->save();
        $sensorGroup->update([
            'group_name' => $data['group_name'],
            'group_value' => $data['group_value']
        ]);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    public function destroy(int $sensorGroupId)
    {
        SensorGroup::findOrFail($sensorGroupId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}
