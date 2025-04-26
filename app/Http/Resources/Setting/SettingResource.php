<?php

namespace App\Http\Resources\Setting;

use App\Http\Resources\Sensor\SensorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="SettingResource",
 *     @OA\Property(property="id", type="integer", description="Setting ID"),
 *     @OA\Property(property="email_notification_allowed", type="boolean", description="Whether email notifications are enabled"),
 *     @OA\Property(property="threshold", type="number", format="float", nullable=true, description="Notification threshold value"),
 *     @OA\Property(property="cooldown", type="integer", nullable=true, description="Notification cooldown period in minutes"),
 *     @OA\Property(property="min_unit_diff", type="number", format="float", nullable=true, description="Minimum unit difference to trigger notification"),
 *     @OA\Property(
 *         property="sensor",
 *         ref="#/components/schemas/SensorResource",
 *         description="Associated sensor"
 *     )
 * )
 */
class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email_notification_allowed' => $this->email_notification_allowed,
            'threshold' => $this->threshold,
            'cooldown' => $this->cooldown,
            'min_unit_diff' => $this->min_unit_diff,
            'sensor' => SensorResource::make($this->sensor)
        ];
    }
}
