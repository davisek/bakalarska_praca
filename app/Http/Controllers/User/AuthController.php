<?php

namespace App\Http\Controllers\User;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Http\Controllers\Controller;
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

        $token = $this->authService->register($registerData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.registration_successful'),
            'user' => UserResource::make(Auth::user()),
            'auth' => $token,
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
                'auth' => $token,
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

    public function metaData()
    {
        return new MetaDataResource(null);
    }
}
