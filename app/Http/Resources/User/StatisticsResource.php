<?php

namespace App\Http\Resources\User;

use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="StatisticsResource",
 *     @OA\Property(property="total_users", type="integer", description="Total number of users"),
 *     @OA\Property(property="admin_users", type="integer", description="Number of admin users"),
 *     @OA\Property(property="total_sensors", type="integer", description="Total number of sensors"),
 *     @OA\Property(property="total_logs", type="integer", description="Total number of logs"),
 *     @OA\Property(property="new_users_today", type="integer", description="Number of new users today"),
 *     @OA\Property(property="new_readings_today", type="integer", description="Number of new sensor readings today"),
 *     @OA\Property(property="new_logs_today", type="integer", description="Number of new logs today")
 * )
 */
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
