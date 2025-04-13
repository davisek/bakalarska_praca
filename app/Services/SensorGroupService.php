<?php
namespace App\Services;

use App\Http\Requests\SensorGroup\SensorGroupStoreRequest;
use App\Http\Requests\SensorGroup\SensorGroupUpdateRequest;
use App\Models\SensorGroup;
use App\Services\Interfaces\ISensorGroupService;
use Illuminate\Support\Facades\Storage;

class SensorGroupService implements ISensorGroupService
{
    public function create(SensorGroupStoreRequest $request)
    {
        $data = $request->validated();

        $sensorGroup = SensorGroup::create($data);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/sensors', 'public');
            $sensorGroup->image_path = $path;
            $sensorGroup->save();
        }
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
        $sensorGroup->update($data);
    }
}
