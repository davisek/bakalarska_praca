<?php
namespace App\Services\Interfaces;

use App\Http\Requests\Sensor\SensorStoreRequest;
use App\Http\Requests\Sensor\SensorUpdateRequest;

interface ISensorService
{
    public function create(SensorStoreRequest $request);

    public function update(int $sensorId, SensorUpdateRequest $request);
}
