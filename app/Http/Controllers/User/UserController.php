<?php

namespace App\Http\Controllers\User;

use App\Data\ChangePasswordData;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserSearchRequestQuery;
use App\Http\Resources\User\StatisticsResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected readonly IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserSearchRequestQuery $request)
    {
        $users = $this->userService->index($request->validated());

        return UserResource::collection($users);
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
        $changePasswordData = ChangePasswordData::from($request->validated());

        $response = $this->userService->updatePassword($changePasswordData);

        return $response;
    }

    public function getStatistics()
    {
        $data = $this->userService->getStatistics();

        return StatisticsResource::make($data);
    }

    public function delete(int $userId)
    {
        User::findOrFail($userId)->delete();

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.deleted_successfully'),
        ]);
    }
}
