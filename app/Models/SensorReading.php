<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @Model SensorReading
 * @property float $temperature
 * @property float $humidity
 * @property float $pressure
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 */
class SensorReading extends Model
{
    use HasFactory;

    protected $table = 'sensor_readings';

    protected $fillable = [
        'temperature',
        'humidity',
        'pressure',
    ];
}
