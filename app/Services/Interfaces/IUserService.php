<?php
namespace App\Services\Interfaces;

use App\Data\ChangePasswordData;
use App\Data\UserData;

interface IUserService
{
    public function index(array $request);

    public function update(UserData $userData);

    public function updatePassword(ChangePasswordData $changePasswordData);

    public function getStatistics();

    public function generateAuthKey();
}
