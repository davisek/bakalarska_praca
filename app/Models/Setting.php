<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @Model Setting
 * @property int $id
 * @property int $user_id
 * @property boolean $temperature_notification
 * @property boolean $humidity_notification
 * @property boolean $pressure_notification
 * @property boolean $in_celsius
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 * @property User $user
 */
class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'temperature_notification',
        'humidity_notification',
        'pressure_notification',
        'in_celsius',
    ];

    protected $casts = [
        'temperature_notification' => 'boolean',
        'humidity_notification' => 'boolean',
        'pressure_notification' => 'boolean',
        'in_celsius' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
