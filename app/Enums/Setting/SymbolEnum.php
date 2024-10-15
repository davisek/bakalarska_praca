<?php

namespace App\Enums\Setting;

enum SymbolEnum: string
{
    case CELSIUS = 'celsius';
    case FAHRENHEIT = 'fahrenheit';

    public function symbol(): string
    {
        return match($this) {
            self::CELSIUS => '°C',
            self::FAHRENHEIT => '°F',
        };
    }
}
