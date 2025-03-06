<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class SettingData extends Data
{
    public function __construct(
        public array $settings,
    ) {}
}

