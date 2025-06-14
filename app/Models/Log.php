<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @Model Log
 * @property int $id
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'message',
    ];
}
