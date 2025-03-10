<?php
namespace App\Services\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ISensorReadingService
{
    public function show(string $sensor_name): Model;

    public function index(string $sensor_name, ?Carbon $from, Carbon $to, int $maxPoints): Collection;

    public function getRawData(string $sensor_name, array $validatedRequest): LengthAwarePaginator;

    public function downloadCsv(string $sensor_name, array $validatedRequest): BinaryFileResponse;

    public function create(array $data);
}
