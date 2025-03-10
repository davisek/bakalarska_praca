<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @Model NotificationSetting
 * @property int $id
 * @property int $user_id
 * @property int $sensor_id
 * @property boolean $email_notification_allowed
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 * @property User $user
 * @property Sensor $sensor
 */
class NotificationSetting extends Model
{
    use HasFactory;

    protected $table = 'notification_settings';

    protected $fillable = [
        'user_id',
        'sensor_id',
        'email_notification_allowed',
    ];

    protected $casts = [
        'email_notification_allowed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}
