<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sensor\SensorStoreRequest;
use App\Http\Requests\Sensor\SensorUpdateRequest;
use App\Http\Resources\EnumResources\MetaDataColorClassResource;
use App\Http\Resources\Sensor\SensorResource;
use App\Models\Sensor;
use App\Services\Interfaces\ISensorService;

/**
 * @OA\Tag(
 *     name="Sensors",
 *     description="API Endpoints for managing sensors"
 * )
 */
class SensorController extends Controller
{
    protected readonly ISensorService $sensorService;

    public function __construct(ISensorService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    /**
     * Get sensor details
     *
     * @OA\Get(
     *     path="/sensors/{sensorId}",
     *     operationId="getSensor",
     *     tags={"Sensors"},
     *     summary="Get sensor details",
     *     description="Returns details for a specific sensor",
     *     @OA\Parameter(
     *         name="sensorId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SensorResource")
     *     ),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function show(int $sensorId)
    {
        $sensor = Sensor::findOrFail($sensorId);

        return SensorResource::make($sensor);
    }

    /**
     * Create a new sensor
     *
     * @OA\Post(
     *     path="/sensors",
     *     operationId="createSensor",
     *     tags={"Sensors"},
     *     summary="Create a new sensor",
     *     description="Creates a new sensor in the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sensor data",
     *         @OA\JsonContent(ref="#/components/schemas/SensorStoreRequest"),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SensorStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Created successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function create(SensorStoreRequest $request)
    {
        $this->sensorService->create($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }

    /**
     * Update a sensor
     *
     * @OA\Post(
     *     path="/sensors/{sensorId}",
     *     operationId="updateSensor",
     *     tags={"Sensors"},
     *     summary="Update an existing sensor",
     *     description="Updates a sensor in the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="sensorId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor to update",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sensor data",
     *         @OA\JsonContent(ref="#/components/schemas/SensorUpdateRequest"),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SensorUpdateRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="Sensor not found"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function update(int $sensorId, SensorUpdateRequest $request)
    {
        $this->sensorService->update($sensorId, $request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    /**
     * Delete a sensor
     *
     * @OA\Delete(
     *     path="/sensors/{sensorId}",
     *     operationId="deleteSensor",
     *     tags={"Sensors"},
     *     summary="Delete a sensor",
     *     description="Deletes a sensor from the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="sensorId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor to delete",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="Sensor not found")
     * )
     */
    public function delete(int $sensorId)
    {
        Sensor::findOrFail($sensorId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }

    /**
     * Get sensor metadata
     *
     * @OA\Get(
     *     path="/sensors/meta-data",
     *     operationId="getSensorMetaData",
     *     tags={"Sensors"},
     *     summary="Get sensor metadata",
     *     description="Returns metadata for sensors, including color classes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="color_classes",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EnumResource")
     *             )
     *         )
     *     )
     * )
     */
    public function metaData()
    {
        return new MetaDataColorClassResource(null);
    }
}