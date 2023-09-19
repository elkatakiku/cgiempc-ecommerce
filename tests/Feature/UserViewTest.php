<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserViewTest extends UserTest
{
    public function test_unauthenticated_user_cannot_view_a_user(): void
    {
        $this->seed(RoleSeeder::class);
        $user = $this->createUser(UserRole::MEMBER);

        $response = $this->getJson(route('users.show', $user->id));

        Log::info($response->content());

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_admin_can_view_other_user_full_information(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = $this->createUser(UserRole::ADMINISTRATOR);
        $user = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($admin)->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'username',
                'name',
                'email',
                'created_at',
                'updated_at',
                'roles',
            ]
        ]);
    }

    public function test_member_can_view_other_member_public_information(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::MEMBER);
        $anotherUser = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)->getJson(route('users.show', $anotherUser->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'username',
                'name',
                'email',
            ]
        ]);
    }

    public function test_user_can_view_own_info(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'username',
                'name',
                'email',
            ]
        ]);
    }

    public function test_returns_error_when_user_not_found(): void
    {
        $this->seed(RoleSeeder::class);

        $user = $this->createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)->getJson(route('users.show', 1));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
