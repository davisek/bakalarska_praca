<?php

namespace App\Http\Resources\SensorReading;

use App\Enums\Setting\SymbolEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

class SensorReadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = null;

        if (request()->bearerToken()) {
            $token = request()->bearerToken();
            if (PersonalAccessToken::findToken($token)) {
                $accessToken = PersonalAccessToken::findToken($token);
                $user = $accessToken->tokenable;
            }
        }

        $value = $this->value;
        $symbol = $this->sensor->unit_of_measurement;
        if ($user && $this->sensor->type == "temperature") {
            if ($user->getTemperatureSymbol() == SymbolEnum::FAHRENHEIT->symbol()) {
                $value = $this->value * (9/5) + 32;
                $symbol = SymbolEnum::FAHRENHEIT->symbol();
            }
        }

        return [
            'value' => $value,
            'symbol' => $symbol,
            'recorded_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
