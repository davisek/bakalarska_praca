<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class LogData extends Data
{
    public function __construct(
        public string $message,
    ) {}
}
