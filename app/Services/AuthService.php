<?php
namespace App\Services;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Enums\Setting\SymbolEnum;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService implements IAuthService
{
    public function register(RegisterData $registerData): array
    {
        $hash = Str::random(150);
        $verification_code = (string)rand(10000, 99999);

        $user = User::create([
            'name' => $registerData->name,
            'surname' => $registerData->surname,
            'email' => $registerData->email,
            'password' => $registerData->password,
            'locale' => $registerData->locale,
            'is_admin' => false,
            'hash' => $hash,
        ]);

        $user->setting()->update([
            'in_celsius' => $registerData->symbol == SymbolEnum::CELSIUS->value,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Cache::store("file")->put($hash, [
            "email" => $user->email,
            "verification_code" => $verification_code ,
        ], now()->addWeek());

        Mail::to($user->email)->send(new VerificationEmail($hash, $verification_code, $user->name . ' ' . $user->surname));

        return [
            'user' => $user,
            'token' => $token,
            'verification_code' => $verification_code,
        ];
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

    public function resendVerificationCode(): string
    {
        $user = Auth::user();

        $oldHash = $user->hash;
        Cache::store('file')->forget($oldHash);

        $new_hash = Str::random(150);
        $verification_code = rand(10000, 99999);
        Cache::store("file")->put($new_hash, [
            "email" => $user->email,
            "verification_code" => $verification_code ,
        ], now()->addWeek());

        $user->hash = $new_hash;
        $user->save();

        Mail::to($user->email)->send(new VerificationEmail($new_hash, $verification_code, $user->name . ' ' . $user->surname));

        return $verification_code;
    }

    public function verifyEmail(VerificationData $verificationData): array
    {
        $cachedData = Cache::store('file')->get($verificationData->hash);

        if (!$cachedData || $cachedData['verification_code'] != $verificationData->verification_code) {
            return [
                'type' => 'error',
                'message_code' => 'errors.verification_failed'
            ];
        }

        $user = User::where('email', $cachedData['email'])->first();

        if (!$user) {
            return [
                'type' => 'error',
                'message_code' => 'errors.user_not_found'
            ];
        }

        if (!is_null($user->email_verified_at)) {
            return [
                'type' => 'error',
                'message_code' => 'errors.already_verified'
            ];
        }

        $user->email_verified_at = now();
        $user->hash = null;
        $user->save();
        Cache::store('file')->forget($verificationData->hash);

        return [
            'type' => 'success',
            'message_code' => 'messages.email_verified'
        ];
    }
}
