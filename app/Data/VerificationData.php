<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class VerificationData extends Data
{
    public function __construct(
        public string $hash,
        public string $verification_code,
    ) {}
}
