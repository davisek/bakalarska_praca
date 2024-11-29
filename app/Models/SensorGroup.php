<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @Model Sensor
 * @property int $id
 * @property string $group_name
 * @property string $group_value
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 * @property Collection|Sensor[] $sensors
 */
class SensorGroup extends Model
{
    use HasFactory;

    protected $table = 'sensor_groups';

    protected $fillable = [
        'group_name',
        'group_value',
    ];

    public function sensors(): HasMany
    {
        return $this->hasMany(Sensor::class, 'sensor_group_id');
    }
}
