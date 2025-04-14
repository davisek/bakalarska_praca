<?php

namespace App\Enums\Sensor;

enum ColorClass: string
{
    case TEMPERATURE_CARD = 'temperature-card';
    case HUMIDITY_CARD = 'humidity-card';
    case PRESSURE_CARD = 'pressure-card';
    case LIGHT_CARD = 'light-card';
    case MOTION_CARD = 'motion-card';
    case AIR_CARD = 'air-card';
    case DEFAULT_CARD = 'default-card';

    public function label(): string
    {
        return trans('enums.color_classes.' . $this->value);

    }

    public function symbol(): ?string
    {
        return null;
    }
}
