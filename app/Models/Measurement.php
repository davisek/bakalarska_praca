<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @Model Measurement
 * @property int $id
 * @property int $sensor_id
 * @property float $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Sensor $sensor
 */
class Measurement extends Model
{
    use HasFactory;

    protected $table = 'measurements';

    protected $fillable = [
        'sensor_id',
        'value',
        'created_at',
        'updated_at',
    ];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}
