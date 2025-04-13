<?php
namespace App\Services;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
            'dark_mode' => $registerData->dark_mode,
            'hash' => $hash,
        ]);

        Cache::store("file")->put($hash, [
            "verification_code" => $verification_code,
        ], now()->addWeek());

        Mail::to($user->email)->send(new VerificationEmail($verification_code, $user->name . ' ' . $user->surname));

        $token = JWTAuth::fromUser($user);

        return array_merge(
            $this->createNewToken($token),
            [
                'verification_code' => $verification_code,
                'user' => $user
            ]
        );
    }

    public function login(LoginData $loginData): ?array
    {
        if (!$token = Auth::guard('api')->attempt($loginData->toArray())) {
            return null;
        }

        return $this->createNewToken($token);
    }

    public function logout(Request $request): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function resendVerificationCode(): string
    {
        $user = Auth::user();

        $oldHash = $user->hash;
        Cache::store('file')->forget($oldHash);

        $new_hash = Str::random(150);
        $verification_code = rand(10000, 99999);
        Cache::store("file")->put($new_hash, [
            "verification_code" => $verification_code ,
        ], now()->addWeek());

        $user->hash = $new_hash;
        $user->save();

        Mail::to($user->email)->send(new VerificationEmail($verification_code, $user->name . ' ' . $user->surname));

        return $verification_code;
    }

    public function verifyEmail(VerificationData $verificationData): array
    {
        $user = Auth::user();

        $cachedData = Cache::store('file')->get($user->hash);

        if (!$cachedData || $cachedData['verification_code'] != $verificationData->verification_code) {
            return [
                'type' => 'error',
                'message_code' => 'errors.verification_failed'
            ];
        }

        if (!is_null($user->email_verified_at)) {
            return [
                'type' => 'error',
                'message_code' => 'errors.already_verified'
            ];
        }

        Cache::store('file')->forget($user->hash);

        $user->email_verified_at = now();
        $user->hash = null;
        $user->save();

        return [
            'type' => 'success',
            'message_code' => 'messages.email_verified'
        ];
    }

    public function refresh(): array
    {
        $token = Auth::guard('api')->refresh();

        return [
            'access_token' => $token,
            'user' => Auth::guard('api')->user()
        ];
    }

    protected function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'user' => Auth::guard('api')->user()
        ];
    }

    public function getUserByLogin(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.invalid_credentials'),
            ]);
        }

        return $user;
    }

    public static function verify(array $data, User $user)
    {
        $cacheCode = Cache::get('forgot_password_code_user' . $user->id);

        if ($cacheCode !== $data['code']) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.verification_failed'),
                'errors' => [
                    'password' => [trans('errors.verification_failed')]
                ]
            ], 422);
        }
        if (Hash::check($user->password, $data['password'])) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.password_same_as_old_one'),
                'errors' => [
                    'password' => [trans('errors.password_same_as_old_one')]
                ]
            ], 422);
        }

        Cache::forget('forgot_password_code_user' . $user->id);

        $user->update([
            'password' => $data['password']
        ]);
    }
}
