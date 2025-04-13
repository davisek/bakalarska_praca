<?php

namespace App\Http\Resources\EnumResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
