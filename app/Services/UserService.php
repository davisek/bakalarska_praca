<?php
namespace App\Services;

use App\Data\ChangePasswordData;
use App\Data\UserData;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    protected readonly IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function update(UserData $userData)
    {
        $user = Auth::user();

        $isMailNew = $user->email !== $userData->email;

        if ($isMailNew) {
            $user->email_verified_at = null;
            $user->save();


        }

        $user->update([
            'name' => $userData->name,
            'surname' => $userData->surname,
            'email' => $userData->email,
            'locale' => $userData->locale,
        ]);

        if ($isMailNew) {
            $this->authService->resendVerificationCode();
        }
    }

    public function updatePassword(ChangePasswordData $changePasswordData)
    {
        $user = Auth::user();

        if (!Hash::check($changePasswordData->current_password, $user->password)) {
            return response()->json([
                'type' => 'error',
                'message' => trans('errors.current_password_incorrect'),
                'errors' => [
                    'current_password' => [trans('errors.current_password_incorrect')]
                ]
            ], 422);
        }

        $user->update([
            'password' => $changePasswordData->password,
        ]);

        return response()->json([
            'type' => 'success',
            'message' => trans('messages.password_updated_successfully'),
        ]);
    }
}
