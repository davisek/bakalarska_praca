<?php
namespace App\Services\Interfaces;

use App\Http\Requests\SensorGroup\SensorGroupStoreRequest;
use App\Http\Requests\SensorGroup\SensorGroupUpdateRequest;

interface ISensorGroupService
{
    public function create(SensorGroupStoreRequest $request);

    public function update(int $sensorGroupId, SensorGroupUpdateRequest $request);
}
