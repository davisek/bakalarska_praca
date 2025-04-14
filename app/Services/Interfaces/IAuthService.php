<?php
namespace App\Services\Interfaces;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IAuthService
{
    public function register(RegisterData $registerData): array;

    public function login(LoginData $loginData): ?array;

    public function logout(Request $request): void;

    public function resendVerificationCode(): string;

    public function verifyEmail(VerificationData $verificationData): array;

    public function refresh(): array;

    public function getUserByLogin(string $email);

    public function verify(array $data, User $user);
}
