<?php

namespace App\Http\Controllers\SensorReading;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorReading\SensorCreateRequest;
use App\Http\Requests\SensorReading\SensorRawRequestQuery;
use App\Http\Requests\SensorReading\SensorRequestQuery;
use App\Http\Resources\SensorReading\SensorReadingResource;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;

class SensorReadingController extends Controller
{
    protected const NUMBER_OF_MEASURES = 40;
    protected readonly ISensorReadingService $sensorReadingService;

    public function __construct(ISensorReadingService $sensorReadingService)
    {
        $this->sensorReadingService = $sensorReadingService;
    }

    public function show(string $sensor)
    {
        $data = $this->sensorReadingService->show($sensor);

        return SensorReadingResource::make($data);
    }

    public function index(string $sensor, SensorRequestQuery $request)
    {
        $validated = $request->validated();
        $to = isset($validated['to'])
            ? Carbon::parse($validated['to'])
            : Carbon::now('Europe/Bratislava');
        $from = isset($validated['from'])
            ? Carbon::parse($validated['from'],'Europe/Bratislava')
            : Carbon::createFromDate(1970, 1, 1, 'Europe/Bratislava');

        $data = $this->sensorReadingService->index($sensor, $from, $to, self::NUMBER_OF_MEASURES);

        return SensorReadingResource::collection($data);
    }

    public function getRawData(string $sensor, SensorRawRequestQuery $request)
    {
        $validatedRequest = $request->validated();
        $data = $this->sensorReadingService->getRawData($sensor, $validatedRequest);
        return response()->json([
            'data' => SensorReadingResource::collection($data),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
        ]);
    }

    public function create(SensorCreateRequest $request)
    {
        $this->sensorReadingService->create($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Sensor reading successfully recorded!',
        ]);
    }
}
