<?php
namespace App\Services;

use App\Data\UserData;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;

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
}
