<?php

namespace App\Enums\SensorReading;

enum MeasurementEnum: float
{
    case SILENT = 0.0;
    case TRIGGERED = 1.0;

    public function label(): string
    {
        return trans('enums.measurements.' . $this->name);
    }

    public function symbol(): ?string
    {
        return null;
    }
}
