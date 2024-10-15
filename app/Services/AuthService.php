<?php
namespace App\Services;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Enums\Setting\SymbolEnum;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService implements IAuthService
{
    public function register(RegisterData $registerData): string
    {
        $user = User::create([
            'name' => $registerData->name,
            'surname' => $registerData->surname,
            'email' => $registerData->email,
            'password' => $registerData->password,
            'locale' => $registerData->locale,
            'is_admin' => false
        ]);

        $user->setting()->update([
            'in_celsius' => $registerData->symbol == SymbolEnum::CELSIUS->value,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function login(LoginData $loginData): ?string
    {
        if (Auth::attempt([
            'email' => $loginData->email,
            'password' => $loginData->password,
        ])) {
            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            return $token;
        }

        return null;
    }

    public function logout(Request $request): void
    {
        $request->user()->tokens()->delete();
    }
}
