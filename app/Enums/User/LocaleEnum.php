<?php

namespace App\Enums\User;

enum LocaleEnum: string
{
    case SLOVAK = 'sk';
    case ENGLISH = 'en';

    public function label(): string
    {
        return trans('enums.locales.' . $this->value);
    }

    public function symbol(): string
    {
        return match($this) {
            self::ENGLISH => asset('icons/UNITED_KINGDOM.svg'),
            self::SLOVAK => asset('icons/SLOVAKIA.svg'),
        };
    }
}
