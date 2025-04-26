<?php

namespace App\Http\Resources\EnumResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EnumResource",
 *     @OA\Property(property="value", type="string", description="Enum value"),
 *     @OA\Property(property="label", type="string", description="Human-readable label"),
 *     @OA\Property(property="symbol", type="string", description="Symbol representation")
 * )
 */
class EnumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'symbol' => $this->symbol(),
        ];
    }
}
