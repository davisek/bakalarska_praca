<?php

namespace App\Http\Controllers\User;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmailVerificationRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\User\UserResource;
use App\Mail\PasswordResetEmail;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected readonly IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $registerData = RegisterData::from($request->validated());

        $data = $this->authService->register($registerData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.registration_successful'),
            'user' => UserResource::make($data['user']),
            'token' => $data['access_token'],
        ]);
    }

    public function login(LoginRequest $request)
    {
        $loginData = LoginData::from($request->validated());
        $token = $this->authService->login($loginData);

        if ($token) {
            return response()->json([
                'type' => 'success',
                'message' => trans('messages.login_successful'),
                'user' => UserResource::make(Auth::user()),
                'token' => $token['access_token'],
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => trans('errors.login_failed'),
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.logout_successful'),
        ]);
    }

    public function resendVerificationCode()
    {
        if (!is_null(Auth::user()->email_verified_at)) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.already_verified'),
            ]);
        }

        $this->authService->resendVerificationCode();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.verification_code_resent'),
        ]);
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $verificationData = VerificationData::from($request->validated());

        $response = $this->authService->verifyEmail($verificationData);

        return response()->json([
            'type' => $response['type'],
            'message' => trans($response['message_code']),
        ]);
    }

    public function refresh()
    {
        $token = $this->authService->refresh();

        if ($token) {
            return response()->json([
                'type' => 'success',
                'message' => trans('messages.login_successful'),
                'user' => UserResource::make(Auth::user()),
                'token' => $token['access_token'],
            ]);
        }

        return null;
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        $user = $this->authService->getUserByLogin($request->validated()['email']);

        $this->sentResetCode($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_reset_email_sent'),
        ]);
    }

    public function resend(ForgotPasswordRequest $request)
    {
        $user = $this->authService->getUserByLogin($request->validated()['email']);

        $this->sentResetCode($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_reset_email_sent'),
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $user = $this->authService->getUserByLogin($data['email']);

        if (!$user) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.verification_failed'),
                'errors' => [
                    'code' => [trans('errors.verification_failed')]
                ]
            ], 422);
        }

        $this->authService->verify($data, $user);
        Auth::login($user);
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.reset_successful'),
            'user' => UserResource::make($user),
            'token' => $token,
        ]);
    }

    public function metaData()
    {
        return new MetaDataResource(null);
    }

    private function sentResetCode(User $user)
    {
        $code = rand(10000, 99999);

        Cache::put(
            'forgot_password_code_user' . $user->id,
            $code,
            now()->addMinutes(5)
        );

        Mail::to($user->email)->send(new PasswordResetEmail($code, $user->name . ' ' . $user->surname));
    }
}
