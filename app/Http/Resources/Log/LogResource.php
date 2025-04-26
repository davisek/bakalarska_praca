<?php

namespace App\Http\Resources\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="LogResource",
 *     @OA\Property(property="message", type="string", description="Log message"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp")
 * )
 */
class LogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
