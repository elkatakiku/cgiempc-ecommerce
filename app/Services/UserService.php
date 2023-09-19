<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(string $name, string $username, string $email, string $password, array $roles): User
    {
        sort($roles);

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role_id' => $roles[0],
        ]);

        $user->roles()->attach($roles);

        return $user;
    }

    public function updateUser(User $user, string $name, string $username, string $email): bool
    {
        return $user->update([
            'name' => $name,
            'email' => $email,
        ]);
    }
}
