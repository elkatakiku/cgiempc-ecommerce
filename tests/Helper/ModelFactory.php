<?php

namespace Tests\Helper;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;

class ModelFactory
{
    public static function createUser(UserRole $role, array $roles = []): User
    {
        $user = User::factory()->create([
            'role_id' => $role->value,
        ]);

        if (empty($roles)) {
            $user->roles()->attach($user->role_id);
        } else {
            $user->roles()->attach($roles);
        }

        return $user;
    }

    public static function createCategory(): Category
    {
        return Category::factory()->create();
    }
}
