<?php
namespace App\Services;

use App\Enums\Setting\SymbolEnum;
use App\Models\SensorReading;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class SensorReadingService implements ISensorReadingService
{
    public function show(string $sensor)
    {
        $latestReading = SensorReading::select($sensor, 'created_at')
            ->whereNotNull($sensor)
            ->latest('created_at')
            ->first();

        $value = $latestReading->$sensor;

        if ($this->getSensorSymbol($sensor) == SymbolEnum::FAHRENHEIT->symbol()) {
            $value = $value * (9/5) + 32;
        }


        $data = [
            'value' => $value,
            'symbol' => $this->getSensorSymbol($sensor),
            'created_at' => $latestReading->created_at,
        ];

        return $data;
    }

    public function index(string $sensor, Carbon $from, Carbon $to, int $maxPoints)
    {
        $from = $from->startOfDay();
        $to = $to->endOfDay();

        $data = SensorReading::select($sensor, 'created_at')
            ->whereNotNull($sensor)
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $totalCount = $data->count();

        if ($totalCount <= $maxPoints) {
            return $data->map(function ($reading) use ($sensor) {
                return [
                    'value' => $reading->$sensor,
                    'created_at' => $reading->created_at,
                    'symbol' => $this->getSensorSymbol($sensor),
                ];
            });
        }

        $intervalSize = $totalCount / $maxPoints;
        $result = collect();

        for ($i = 0; $i < $maxPoints; $i++) {
            $startIndex = floor($i * $intervalSize);
            $intervalData = $data->slice($startIndex, ceil($intervalSize));

            $averageValue = $intervalData->avg($sensor);
            $firstTimestamp = $intervalData->first()->created_at;

            $result->push([
                'value' => $averageValue,
                'created_at' => $firstTimestamp,
                'symbol' => $this->getSensorSymbol($sensor),
            ]);
        }

        return $result;
    }



    public function getRawData(string $sensor, Carbon $from, Carbon $to, int $maxPoints)
    {
        $data = SensorReading::select($sensor . ' as value', 'created_at')
            ->whereNotNull($sensor)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $data->each(function ($reading) use ($sensor) {
            $reading->symbol = $this->getSensorSymbol($sensor);

            if ($this->getSensorSymbol($sensor) == SymbolEnum::FAHRENHEIT->symbol()) {
                $reading->value = $reading->value * (9/5) + 32;
            }
        });

        return $data;
    }

    private function getSensorSymbol(string $sensor): string
    {
        $user = null;

        if (request()->bearerToken()) {
            $token = request()->bearerToken();
            if (PersonalAccessToken::findToken($token)) {
                $accessToken = PersonalAccessToken::findToken($token);
                $user = $accessToken->tokenable;
            }
        }

        return match ($sensor) {
            'temperature' => $user ? $user->getTemperatureSymbol() : SymbolEnum::CELSIUS->symbol(),
            'humidity' => '%',
            'pressure' => 'hPa',
            default => '',
        };
    }
}
