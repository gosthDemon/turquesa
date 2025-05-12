<?php

namespace App\Repositories;

use App\Services\Service;
use App\Models\User;
use App\DTOs\User\UserDTO;

class UserRepository extends Repository
{
    public function getAll()
    {
        $users = User::with("created_by", "updated_by", "role")->get();
        $usersListDTO = UserDTO::ToDTOList($users->toArray());
        return $usersListDTO;
    }
    public function getUserByEmail($email): UserDTO|null
    {
        $user = User::with("role")->where('email', $email)->first();
        if (!$user) return null;
        $userDTO = UserDTO::ToDTO($user->toArray());
        return $userDTO;
    }
    public function getUserById($id): UserDTO|null
    {
        $user = User::with("created_by", "updated_by", "role")->find($id);
        if ($user == null) return null;
        $userDTO = UserDTO::ToDTO($user->toArray());
        return $userDTO;
    }
}