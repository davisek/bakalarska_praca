<?php

namespace App\Http\Controllers\SensorReading;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorReading\SensorRequestQuery;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\SensorReading\SensorLinksResource;
use App\Http\Resources\SensorReading\SensorReadingResource;
use App\Models\SensorGroup;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;

class MetaDataController extends Controller
{
    public function sensorGroups()
    {
        $data = SensorGroup::with('sensors')->get();

        return SensorLinksResource::collection($data);
    }

    public function sensors(string $sensorGroup)
    {
        $data = SensorGroup::with('sensors')
            ->where('group_value', $sensorGroup)
            ->first();

        return SensorLinksResource::make($data);
    }

    public function metaData()
    {
        return new MetaDataResource(null);
    }
}
