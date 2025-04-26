<?php

namespace App\Http\Controllers\SensorReading;

use App\Http\Controllers\Controller;
use App\Http\Requests\SensorReading\SensorCsvRequestQuery;
use App\Http\Requests\SensorReading\SensorReadingCreateRequest;
use App\Http\Requests\SensorReading\SensorRawRequestQuery;
use App\Http\Requests\SensorReading\SensorRequestQuery;
use App\Http\Resources\SensorReading\SensorReadingResource;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Sensor Readings",
 *     description="API Endpoints for managing sensor readings"
 * )
 */
class SensorReadingController extends Controller
{
    protected const NUMBER_OF_MEASURES = 40;
    protected readonly ISensorReadingService $sensorReadingService;

    public function __construct(ISensorReadingService $sensorReadingService)
    {
        $this->sensorReadingService = $sensorReadingService;
    }

    /**
     * Get latest sensor reading
     *
     * @OA\Get(
     *     path="/sensor-readings/{sensor}",
     *     operationId="getLatestSensorReading",
     *     tags={"Sensor Readings"},
     *     summary="Get latest sensor reading",
     *     description="Returns the latest reading for a specific sensor",
     *     @OA\Parameter(
     *         name="sensor",
     *         in="path",
     *         required=true,
     *         description="Sensor identifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SensorReadingResource")
     *     ),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function show(string $sensor)
    {
        $data = $this->sensorReadingService->show($sensor);

        return SensorReadingResource::make($data);
    }

    /**
     * Get sensor readings collection
     *
     * @OA\Get(
     *     path="/sensor-readings/collection/{sensor}",
     *     operationId="getSensorReadingsCollection",
     *     tags={"Sensor Readings"},
     *     summary="Get sensor readings collection",
     *     description="Returns a collection of readings for a specific sensor within a time range, limited to 40 points",
     *     @OA\Parameter(
     *         name="sensor",
     *         in="path",
     *         required=true,
     *         description="Sensor identifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Start date for readings (defaults to 1970-01-01)",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         description="End date for readings (defaults to current time)",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SensorReadingResource")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function index(string $sensor, SensorRequestQuery $request)
    {
        $validated = $request->validated();
        $to = isset($validated['to'])
            ? Carbon::parse($validated['to'])
            : Carbon::now('Europe/Bratislava');
        $from = isset($validated['from'])
            ? Carbon::parse($validated['from'], 'Europe/Bratislava')
            : Carbon::createFromDate(1970, 1, 1, 'Europe/Bratislava');

        $data = $this->sensorReadingService->index($sensor, $from, $to, self::NUMBER_OF_MEASURES);

        return SensorReadingResource::collection($data);
    }

    /**
     * Get raw sensor readings data
     *
     * @OA\Get(
     *     path="/sensor-readings/collection/{sensor}/raw",
     *     operationId="getRawSensorReadings",
     *     tags={"Sensor Readings"},
     *     summary="Get raw sensor readings data",
     *     description="Returns raw paginated data for a specific sensor within a time range",
     *     @OA\Parameter(
     *         name="sensor",
     *         in="path",
     *         required=true,
     *         description="Sensor identifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Start date for readings",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         description="End date for readings",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         description="Field to sort by",
     *         @OA\Schema(type="string", enum={"value", "created_at"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_dir",
     *         in="query",
     *         required=false,
     *         description="Sort direction",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/SensorReadingResource")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function getRawData(string $sensor, SensorRawRequestQuery $request)
    {
        $data = $this->sensorReadingService->getRawData($sensor, $request->validated());

        return SensorReadingResource::collection($data);
    }

    /**
     * Download sensor readings as CSV
     *
     * @OA\Get(
     *     path="/sensor-readings/collection/{sensor}/download",
     *     operationId="downloadSensorReadingsCsv",
     *     tags={"Sensor Readings"},
     *     summary="Download sensor readings as CSV",
     *     description="Generates and downloads a CSV file with readings for a specific sensor within a time range",
     *     @OA\Parameter(
     *         name="sensor",
     *         in="path",
     *         required=true,
     *         description="Sensor identifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Start date for readings",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         description="End date for readings",
     *         @OA\Schema(type="string", format="date-time")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="CSV file download",
     *         @OA\MediaType(
     *             mediaType="text/csv",
     *             @OA\Schema(
     *                 type="string",
     *                 format="binary"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function downloadCsv(string $sensor, SensorCsvRequestQuery $request)
    {
        $xlxs = $this->sensorReadingService->downloadCsV($sensor, $request->validated());

        return $xlxs;
    }

    /**
     * Create sensor reading
     *
     * @OA\Post(
     *     path="/sensor-readings",
     *     operationId="createSensorReading",
     *     tags={"Sensor Readings"},
     *     summary="Create a new sensor reading",
     *     description="Records a new reading for a specific sensor",
     *     security={{"sensorAdminKey":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SensorReadingCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sensor reading successfully recorded!")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized - Missing or invalid admin key"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function create(SensorReadingCreateRequest $request)
    {
        $this->sensorReadingService->create($request->validated());

        return response()->json([
            'type' => 'success',
            'message' => 'Sensor reading successfully recorded!',
        ]);
    }
}