<?php

namespace App\Http\Resources\User;

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
            'locales' => LocaleEnum::cases(),
            'symbols' => SymbolEnum::cases(),
        ];
    }
}
