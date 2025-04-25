<?php
namespace App\Services;

use App\Models\Measurement;
use App\Models\Sensor;
use App\Services\Interfaces\ISensorReadingService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Spatie\SimpleExcel\SimpleExcelWriter;

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

        if (!$sensor) {
            return collect();
        }

        $data = Measurement::with('sensor')
            ->select('sensor_id', 'value', 'created_at')
            ->where('sensor_id', $sensor->id)
            ->whereNotNull('value')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $totalCount = $data->count();

        if ($totalCount <= $maxPoints) {
            return $data;
        }

        $intervalSize = $totalCount / $maxPoints;

        $result = collect(range(0, $maxPoints - 1))->map(function ($i) use ($data, $intervalSize, $sensor) {
            $startIndex = floor($i * $intervalSize);
            $intervalData = $data->slice($startIndex, ceil($intervalSize));

            $firstMeasurement = $intervalData->first();

            if (!$firstMeasurement) {
                return null;
            }

            if ($sensor->is_output_binary) {
                $countTriggered = $intervalData->filter(function ($m) {
                    return $m->value === 1.0;
                })->count();

                $firstMeasurement->value = $countTriggered;
            } else {
                $firstMeasurement->value = round($intervalData->avg('value'), 2);
            }

            return $firstMeasurement;
        })->filter();

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

    public function downloadCsv(string $sensor_name, array $validatedRequest): BinaryFileResponse
    {
        $to = isset($validatedRequest['to'])
            ? Carbon::parse($validatedRequest['to'], 'Europe/Bratislava')->endOfDay()
            : Carbon::now('Europe/Bratislava');
        $from = isset($validatedRequest['from'])
            ? Carbon::parse($validatedRequest['from'], 'Europe/Bratislava')
            : Carbon::createFromDate(1970, 1, 1, 'Europe/Bratislava');

        $sensor = Sensor::where('type', $sensor_name)->first();

        $measurements = Measurement::with('sensor')
            ->select('sensor_id', 'value', 'created_at')
            ->where('sensor_id', $sensor->id)
            ->whereNotNull('value')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $tempFilePath = Storage::path('temp/' . Str::random(16) . '.xlsx');

        if (!file_exists(dirname($tempFilePath))) {
            mkdir(dirname($tempFilePath), 0755, true);
        }

        $writer = SimpleExcelWriter::create($tempFilePath, 'xlsx')
            ->addHeader(['created_at', 'value']);

        $measurements->each(function ($measurement) use ($writer) {
            $writer->addRow([
                'created_at' => $measurement->created_at->format('Y-m-d H:i:s'),
                'value' => $measurement->value,
            ]);
        });

        $writer->close();

        $filename = sprintf(
            '%s_measurements_%s_to_%s.xlsx',
            $sensor_name,
            $from->format('Y-m-d'),
            $to->format('Y-m-d')
        );

        $response = new BinaryFileResponse($tempFilePath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        $response->deleteFileAfterSend(true);

        return $response;
    }

    public function create(array $data)
    {
        $sensor = Sensor::where('type', $data['sensor_name'])->first();

        Measurement::create([
            'sensor_id' => $sensor->id,
            'value' => $data['value'],
        ]);
    }
}
