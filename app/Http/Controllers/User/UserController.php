<?php

namespace App\Http\Controllers\User;

use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected readonly IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function show()
    {
        $user = Auth::user();

        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $userData = UserData::from($request->validated());

        $this->userService->update($userData);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.profile_updated_successfully'),
        ]);
    }

    public function changePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => $request->validated()['password'],
        ]);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_updated_successfully'),
        ]);
    }
}
