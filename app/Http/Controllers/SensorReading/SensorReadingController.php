<?php

namespace App\Http\Controllers\SensorReading;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorReading\SensorRequest;
use App\Http\Resources\SensorReading\SensorReadingResource;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;

class SensorReadingController extends Controller
{
    protected const NUMBER_OF_MEASURES = 24;
    protected readonly ISensorReadingService $sensorReadingService;

    public function __construct(ISensorReadingService $sensorReadingService)
    {
        $this->sensorReadingService = $sensorReadingService;
    }

    public function show(SensorRequest $request)
    {
        $data = $this->sensorReadingService->show($request->validated()['sensor']);

        return SensorReadingResource::make($data);
    }

    public function index(SensorRequest $request)
    {
        $sensor = $request->validated()['sensor'];
        $from = $request->validated()['from'] ? Carbon::parse($request->validated()['from']) : Carbon::now()->subDay();
        $to = $request->validated()['to'] ? Carbon::parse($request->validated()['to']) : Carbon::now();

        $data = $this->sensorReadingService->index($sensor, $from, $to, self::NUMBER_OF_MEASURES);

        return SensorReadingResource::collection($data);
    }

    public function getRawData(SensorRequest $request)
    {
        $sensor = $request->validated()['sensor'];
        $from = $request->validated()['from'] ? Carbon::parse($request->validated()['from']) : Carbon::now()->subDay();
        $to = $request->validated()['to'] ? Carbon::parse($request->validated()['to']) : Carbon::now();

        $data = $this->sensorReadingService->getRawData($sensor, $from, $to, self::NUMBER_OF_MEASURES);

        return SensorReadingResource::collection($data);
    }
}
