<?php

namespace App\Http\Controllers\Log;

use App\Data\LogData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Log\LogRequest;
use App\Http\Requests\Log\LogSearchRequestQuery;
use App\Http\Resources\Log\LogResource;
use App\Services\Interfaces\ILogService;

class LogController extends Controller
{
    protected readonly ILogService $logService;

    public function __construct(ILogService $logService)
    {
        $this->logService = $logService;
    }

    public function index(LogSearchRequestQuery $request)
    {
        $logs = $this->logService->index($request->validated());

        return LogResource::collection($logs);
    }

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
