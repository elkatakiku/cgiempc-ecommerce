<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(string $name, string $email, string $password, array $roles): User
    {
        sort($roles);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => $roles[0],
        ]);

        $user->roles()->attach($roles);

        return $user;
    }
}
