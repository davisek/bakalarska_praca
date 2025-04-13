<?php
namespace App\Services\Interfaces;

use App\Data\LogData;

interface ILogService
{
    public function index(array $request);

    public function create(LogData $logData);
}
