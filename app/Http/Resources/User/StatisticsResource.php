<?php

namespace App\Http\Resources\User;

use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_users' => $this['totalUsers'],
            'admin_users' => $this['adminUsers'],
            'total_sensors' => $this['totalSensors'],
            'total_logs' => $this['totalLogs'],
            'new_users_today' => $this['newUsersToday'],
            'new_readings_today' => $this['newReadingsToday'],
            'new_logs_today' => $this['newLogsToday'],
        ];
    }
}
