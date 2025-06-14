<?php

namespace App\Models;

use App\Enums\Sensor\ColorClass;
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
 * @property string $display_name
 * @property string $unit_of_measurement
 * @property boolean $is_output_binary
 * @property string $image_path
 * @property string $icon_path
 * @property ColorClass $color_class
 * @property int $sensor_group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
        'is_output_binary',
        'image_path',
        'icon_path',
        'color_class',
        'sensor_group_id',
    ];

    protected $casts = [
        'is_output_binary' => 'boolean',
        'color_class' => ColorClass::class
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
