<?php

namespace App\Http\Resources\EnumResources;

use App\Enums\Sensor\ColorClass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaDataColorClassResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'color_classes' => EnumResource::collection(ColorClass::cases()),
        ];
    }
}
