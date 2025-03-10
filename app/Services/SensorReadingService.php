<?php
namespace App\Services;

use App\Models\Measurement;
use App\Models\Sensor;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SensorReadingService implements ISensorReadingService
{
    const PER_PAGE = 15;

    public function show(string $sensor_name): Model
    {
        $sensor = Sensor::where('type', $sensor_name)->first();

        $data = Measurement::with('sensor')
            ->select('sensor_id', 'value', 'created_at')
            ->where('sensor_id', $sensor->id)
            ->whereNotNull('value')
            ->latest('created_at')
            ->first();

        return $data;
    }

    public function index(string $sensor_name, ?Carbon $from, Carbon $to, int $maxPoints): Collection
    {
        $from = $from ?: Carbon::createFromDate(1970, 1, 1);
        $to = $to->endOfDay();

        $sensor = Sensor::where('type', $sensor_name)->first();

        if ($sensor) {
            $data = Measurement::with('sensor')
                ->select('sensor_id', 'value', 'created_at')
                ->where('sensor_id', $sensor->id)
                ->whereNotNull('value')
                ->whereBetween('created_at', [$from, $to])
                ->orderBy('created_at')
                ->get();
        } else {
            $data = collect();
        }

        $totalCount = $data->count();

        if ($totalCount <= $maxPoints) {
            return $data;
        }

        $intervalSize = $totalCount / $maxPoints;

        $result = collect(range(0, $maxPoints - 1))->map(function ($i) use ($data, $intervalSize) {
            $startIndex = floor($i * $intervalSize);
            $intervalData = $data->slice($startIndex, ceil($intervalSize));

            $firstMeasurement = $intervalData->first();
            if ($firstMeasurement) {
                $firstMeasurement->value = $intervalData->avg('value');
            }

            return $firstMeasurement;
        });

        $lastResult = $result->last();
        $lastData = $data->last();

        if ($lastData && (!$lastResult || $lastResult->created_at->ne($lastData->created_at))) {
            $result->push($lastData);
        }

        return $result;
    }

    public function getRawData(string $sensor_name, array $validatedRequest): LengthAwarePaginator
    {
        $to = isset($validatedRequest['to'])
            ? Carbon::parse($validatedRequest['to'], 'Europe/Bratislava')->endOfDay()
            : Carbon::now('Europe/Bratislava');
        $from = isset($validatedRequest['from'])
            ? Carbon::parse($validatedRequest['from'], 'Europe/Bratislava')
            : Carbon::createFromDate(1970, 1, 1, 'Europe/Bratislava');

        $perPage = $validatedRequest['per_page'] ?? self::PER_PAGE;

        $sensor = Sensor::where('type', $sensor_name)->first();

        $query = Measurement::with('sensor')
            ->select('sensor_id', 'value', 'created_at')
            ->where('sensor_id', $sensor->id)
            ->whereNotNull('value')
            ->whereBetween('created_at', [$from, $to]);

        $sortBy = $validatedRequest['sort_by'] ?: 'created_at';
        $sortDir = $validatedRequest['sort_dir'] ?: 'asc';

        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        $sensor = Sensor::where('type', $data['sensor_name'])->first();

        $timestamp = Carbon::now();

        Measurement::create([
            'sensor_id' => $sensor->id,
            'value' => $data['value'],
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }
}
