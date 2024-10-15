<?php

namespace App\Http\Controllers\User;

use App\Data\LoginData;
use App\Data\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
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
            'message' => 'Registration successful',
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
                'message' => 'Login successful.',
                'user' => UserResource::make(Auth::user()),
                'auth' => $token,
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Login failed. Invalid credentials.'
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return response()->json([
            'type' => 'success',
            'message' => 'Logout successful.'
        ]);
    }
}
