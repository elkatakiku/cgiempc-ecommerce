<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Database\Seeders\RoleSeeder;
use Symfony\Component\HttpFoundation\Response;

class UserUpdateTest extends UserTest
{
    public function test_unauthenticated_user_cannot_access_update_user(): void
    {
        $this->seed(RoleSeeder::class);

        $response = $this->putJson(route(
            'users.update',
            $this->createUser(UserRole::MEMBER)->id
        ));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_update_their_account(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)
            ->deleteJson(route('users.update', $user->id), [
                'name' => 'Updated User'
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_admin_can_update_other_user(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::ADMINISTRATOR);
        $otherUser = $this->createUser(UserRole::ADMINISTRATOR);

        $response = $this->actingAs($user)
            ->deleteJson(route('users.update', $otherUser->id), [
                'name' => 'Updated User',
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_member_cannot_update_other_user(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::MEMBER);
        $otherUser = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)
            ->deleteJson(route('users.update', $otherUser->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
