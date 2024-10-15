<?php
namespace App\Services\Interfaces;

use App\Data\LoginData;
use App\Data\RegisterData;
use Illuminate\Http\Request;

interface IAuthService
{
    public function register(RegisterData $registerData): string;

    public function login(LoginData $loginData): ?string;

    public function logout(Request $request): void;
}
