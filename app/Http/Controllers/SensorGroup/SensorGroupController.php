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

/**
 * @OA\Tag(
 *     name="Sensor Groups",
 *     description="API Endpoints for managing sensor groups"
 * )
 */
class SensorGroupController extends Controller
{
    protected readonly ISensorGroupService $sensorGroupService;

    public function __construct(ISensorGroupService $sensorGroupService)
    {
        $this->sensorGroupService = $sensorGroupService;
    }

    /**
     * Get all sensor groups
     *
     * @OA\Get(
     *     path="/sensor-groups",
     *     operationId="getSensorGroups",
     *     tags={"Sensor Groups"},
     *     summary="Get all sensor groups",
     *     description="Returns a list of all sensor groups with their sensors",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SensorLinksResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $data = SensorGroup::with('sensors')->get();

        return SensorLinksResource::collection($data);
    }

    /**
     * Get sensor group details
     *
     * @OA\Get(
     *     path="/sensor-groups/{sensorGroupId}",
     *     operationId="getSensorGroup",
     *     tags={"Sensor Groups"},
     *     summary="Get sensor group details",
     *     description="Returns details for a specific sensor group",
     *     @OA\Parameter(
     *         name="sensorGroupId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor group",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SensorLinksResource")
     *     ),
     *     @OA\Response(response=404, description="Sensor group not found")
     * )
     */
    public function show(int $sensorGroupId)
    {
        $data = SensorGroup::with('sensors')->findOrFail($sensorGroupId);

        return SensorLinksResource::make($data);
    }

    /**
     * Create a new sensor group
     *
     * @OA\Post(
     *     path="/sensor-groups",
     *     operationId="createSensorGroup",
     *     tags={"Sensor Groups"},
     *     summary="Create a new sensor group",
     *     description="Creates a new sensor group in the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sensor group data",
     *         @OA\JsonContent(ref="#/components/schemas/SensorGroupStoreRequest"),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SensorGroupStoreRequest")
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
    public function create(SensorGroupStoreRequest $request)
    {
        $this->sensorGroupService->create($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }

    /**
     * Update a sensor group
     *
     * @OA\Post(
     *     path="/sensor-groups/{sensorGroupId}",
     *     operationId="updateSensorGroup",
     *     tags={"Sensor Groups"},
     *     summary="Update an existing sensor group",
     *     description="Updates a sensor group in the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="sensorGroupId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor group to update",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sensor group data",
     *         @OA\JsonContent(ref="#/components/schemas/SensorGroupUpdateRequest"),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/SensorGroupUpdateRequest")
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
     *     @OA\Response(response=404, description="Sensor group not found"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     * )
     */
    public function update(int $sensorGroupId, SensorGroupUpdateRequest $request)
    {
        $this->sensorGroupService->update($sensorGroupId, $request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.updated_successfully'),
        ]);
    }

    /**
     * Delete a sensor group
     *
     * @OA\Delete(
     *     path="/sensor-groups/{sensorGroupId}",
     *     operationId="deleteSensorGroup",
     *     tags={"Sensor Groups"},
     *     summary="Delete a sensor group",
     *     description="Deletes a sensor group from the system",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="sensorGroupId",
     *         in="path",
     *         required=true,
     *         description="ID of the sensor group to delete",
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
     *     @OA\Response(response=404, description="Sensor group not found")
     * )
     */
    public function delete(int $sensorGroupId)
    {
        SensorGroup::findOrFail($sensorGroupId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}