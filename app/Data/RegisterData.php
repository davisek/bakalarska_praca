<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class RegisterData extends Data
{
    public function __construct(
        public string $name,
        public string $surname,
        public string $email,
        public string $password,
        public string $locale,
        public bool $dark_mode,
    ) {}
}

