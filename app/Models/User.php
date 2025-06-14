<?php

namespace App\Models;

use App\Enums\User\LocaleEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * @Model User
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $hash
 * @property LocaleEnum $locale
 * @property boolean $is_admin
 * @property boolean $dark_mode
 * @property string $auth_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|NotificationSetting[] $notificationSettings
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'locale',
        'hash',
        'is_admin',
        'dark_mode',
        'auth_key',
    ];

    protected $hidden = [
        'password',
        'auth_key',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dark_mode' => 'boolean',
        'locale' => LocaleEnum::class
    ];

    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSetting::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
