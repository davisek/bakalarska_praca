<?php

namespace App\Http\Controllers\Log;

use App\Data\LogData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Log\LogRequest;
use App\Http\Requests\Log\LogSearchRequestQuery;
use App\Http\Resources\Log\LogResource;
use App\Services\Interfaces\ILogService;

/**
 * @OA\Tag(
 *     name="Logs",
 *     description="API Endpoints for managing logs"
 * )
 */
class LogController extends Controller
{
    protected readonly ILogService $logService;

    public function __construct(ILogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Get log entries
     *
     * @OA\Get(
     *     path="/logs",
     *     operationId="getLogs",
     *     tags={"Logs"},
     *     summary="Get log entries",
     *     description="Returns paginated list of log entries",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(ref="#/components/parameters/PageParameter"),
     *     @OA\Parameter(ref="#/components/parameters/PerPageParameter"),
     *     @OA\Parameter(ref="#/components/parameters/SearchParameter"),
     *     @OA\Parameter(ref="#/components/parameters/SortByParameter"),
     *     @OA\Parameter(ref="#/components/parameters/SortDirParameter"),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/LogResourceCollection")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     *     )
     */
    public function index(LogSearchRequestQuery $request)
    {
        $logs = $this->logService->index($request->validated());

        return LogResource::collection($logs);
    }

    /**
     * Create log entry
     *
     * @OA\Post(
     *     path="/logs",
     *     operationId="createLog",
     *     tags={"Logs"},
     *     summary="Create a new log entry",
     *     description="Creates a new log entry in the system",
     *     security={{"sensorAdminKey":{}}},
     *     @OA\RequestBody(ref="#/components/requestBodies/LogCreateRequest"),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="type", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Created successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized - Missing or invalid admin key"),
     *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse"))
     *     )
     */
    public function create(LogRequest $request)
    {
        $logData = LogData::from($request->validated());

        $this->logService->create($logData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.created_successfully'),
        ]);
    }
}
