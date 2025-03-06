<?php
namespace App\Services\Interfaces;

use App\Data\ChangePasswordData;
use App\Data\UserData;

interface IUserService
{
    public function update(UserData $userData);

    public function updatePassword(ChangePasswordData $changePasswordData);
}
