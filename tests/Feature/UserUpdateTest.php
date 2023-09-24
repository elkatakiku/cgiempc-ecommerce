<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Database\Seeders\RoleSeeder;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_update_user(): void
    {
        $this->seed(RoleSeeder::class);

        $response = $this->putJson(route(
            'users.update',
            ModelFactory::createUser(UserRole::MEMBER)->id
        ));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_update_their_account(): void
    {
        $this->seed(RoleSeeder::class);

        $user = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)
            ->putJson(route('users.update', $user->id), [
                'name' => 'Updated User'
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_admin_can_update_other_user(): void
    {
        $this->seed(RoleSeeder::class);

        $user = ModelFactory::createUser(UserRole::ADMINISTRATOR);
        $otherUser = ModelFactory::createUser(UserRole::ADMINISTRATOR);

        $response = $this->actingAs($user)
            ->putJson(route('users.update', $otherUser->id), [
                'name' => 'Updated User',
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_member_cannot_update_other_user(): void
    {
        $this->seed(RoleSeeder::class);

        $user = ModelFactory::createUser(UserRole::MEMBER);
        $otherUser = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)
            ->putJson(route('users.update', $otherUser->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
