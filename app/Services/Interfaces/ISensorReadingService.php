<?php
namespace App\Services\Interfaces;

use Carbon\Carbon;

interface ISensorReadingService
{
    public function show(string $sensor);

    public function index(string $sensor, Carbon $from, Carbon $to, int $maxPoints);
}
