<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(UserRole $role, array $roles = []): User
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
}
