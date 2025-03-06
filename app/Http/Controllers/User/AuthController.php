<?php

namespace App\Http\Controllers\User;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Data\VerificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\EmailVerificationRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\EnumResources\MetaDataResource;
use App\Http\Resources\User\UserResource;
use App\Services\Interfaces\IAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'token' => $data['token'],
            'verification_code' => $data['verification_code'],
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

        $verificationCode = $this->authService->resendVerificationCode();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.verification_code_resent'),
            'verification_code' => $verificationCode,
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
    }
}
