<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class ChangePasswordData extends Data
{
    public function __construct(
        public string $current_password,
        public string $password,
    ) {}
}
