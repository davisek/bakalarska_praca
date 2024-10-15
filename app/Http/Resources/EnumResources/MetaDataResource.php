<?php

namespace App\Http\Resources\EnumResources;

use App\Enums\Setting\SymbolEnum;
use App\Enums\User\LocaleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'locales' => EnumResource::collection(LocaleEnum::cases()),
            'symbols' => EnumResource::collection(SymbolEnum::cases()),
        ];
    }
}
