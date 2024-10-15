<?php
namespace App\Services;

use App\Data\UserData;
use App\Services\Interfaces\IUserService;
use Illuminate\Support\Facades\Auth;

class UserService implements IUserService
{
    public function update(UserData $userData)
    {
        $user = Auth::user();

        $user->update([
            'name' => $userData->name,
            'surname' => $userData->surname,
            'email' => $userData->email,
            'locale' => $userData->locale,
        ]);
    }
}
