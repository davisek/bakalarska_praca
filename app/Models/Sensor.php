<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @Model Sensor
 * @property int $id
 * @property string $sensor_name
 * @property string $type
 * @property string $unit_of_measurement
 * @property string $image_path
 * @property string $icon_path
 * @property int $sensor_group_id
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 * @property Collection|Measurement[] $measurements
 * @property SensorGroup $sensorGroup
 */
class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensors';

    protected $fillable = [
        'sensor_name',
        'type',
        'display_name',
        'unit_of_measurement',
        'image_path',
        'icon_path',
        'sensor_group_id',
    ];

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class, 'sensor_id');
    }

    public function sensorGroup(): BelongsTo
    {
        return $this->belongsTo(SensorGroup::class, 'sensor_group_id');
    }

    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class, 'sensor_id');
    }
}
