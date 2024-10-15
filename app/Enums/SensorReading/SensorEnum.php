<?php

namespace App\Enums\SensorReading;

enum SensorEnum: string
{
    case TEMPERATURE = 'temperature';
    case HUMIDITY = 'humidity';
    case PRESSURE = 'pressure';
}
