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
        $totalDuration = $to->timestamp - $from->timestamp;
        $interval = floor($totalDuration / ($maxPoints - 1));

        $data = SensorReading::selectRaw("
            AVG($sensor) as value,
            MIN(created_at) as created_at
        ")
            ->whereNotNull($sensor)
            ->whereBetween('created_at', [$from, $to])
            ->groupByRaw("FLOOR(UNIX_TIMESTAMP(created_at) / $interval)")
            ->get();

        $data->each(function ($reading) use ($sensor) {
            $reading->symbol = $this->getSensorSymbol($sensor);

            if ($this->getSensorSymbol($sensor) == SymbolEnum::FAHRENHEIT->symbol()) {
                $reading->value = $reading->value * (9/5) + 32;
            }
        });

        $lastReading = SensorReading::select($sensor, 'created_at')
            ->whereNotNull($sensor)
            ->where('created_at', '<=', $to)
            ->latest('created_at')
            ->first();

        $exists = $data->contains(function ($reading) use ($lastReading) {
            return $reading->created_at->toDateString() === $lastReading->created_at->toDateString();
        });

        if ($lastReading && !$exists) {
            $newReading = new SensorReading();
            $newReading->value = $lastReading->$sensor;
            $newReading->created_at = $lastReading->created_at;
            $newReading->symbol = $this->getSensorSymbol($sensor);

            $data->push($newReading);
        }

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
