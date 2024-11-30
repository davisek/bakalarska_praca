<?php

namespace App\Models;

use App\Enums\Setting\SymbolEnum;
use App\Enums\User\LocaleEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @Model User
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property Carbon|Null $email_verified_at
 * @property string $password
 * @property string $hash
 * @property string $locale
 * @property boolean $is_admin
 * @property boolean $in_celsius
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
 * @property Collection|NotificationSetting[] $notificationSettings
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'locale',
        'is_admin',
        'in_celsius',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'in_celsius' => 'boolean',
        'locale' => LocaleEnum::class
    ];

    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class, 'user_id');
    }

    public function getTemperatureSymbol()
    {
        return $this->setting->in_celsius ? SymbolEnum::CELSIUS->symbol() : SymbolEnum::FAHRENHEIT->symbol();
    }
}
