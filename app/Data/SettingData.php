<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class SettingData extends Data
{
    public function __construct(
        public bool $temperature_notification,
        public bool $humidity_notification,
        public bool $pressure_notification,
        public bool $in_celsius,
    ) {}
}

