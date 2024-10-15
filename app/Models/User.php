<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Setting\SymbolEnum;
use App\Enums\User\LocaleEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
 * @property Carbon|Null $created_at
 * @property Carbon|Null $updated_at
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
        'locale'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'locale' => LocaleEnum::class
    ];

    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    public function getTemperatureSymbol()
    {
        return $this->setting->in_celsius ? SymbolEnum::CELSIUS->symbol() : SymbolEnum::FAHRENHEIT->symbol();
    }
}
