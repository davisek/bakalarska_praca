<?php
namespace App\Services\Interfaces;

use App\Data\UserData;

interface IUserService
{
    public function update(UserData $userData);

}
