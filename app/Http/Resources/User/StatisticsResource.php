<?php

namespace App\Http\Resources\User;

use App\Http\Resources\EnumResources\EnumResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_users' => $this['totalUsers'],
            'admin_users' => $this['adminUsers'],
            'total_sensors' => $this['totalSensors'],
            'new_users_today' => $this['newUsersToday'],
            'new_readings_today' => $this['newReadingsToday'],
        ];
    }
}
