<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use Database\Seeders\RoleSeeder;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class UserViewTest extends TestCase
{
    public function test_unauthenticated_user_cannot_view_a_user(): void
    {
        $this->seed(RoleSeeder::class);
        $user = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_admin_can_view_other_user_full_information(): void
    {
        $this->seed(RoleSeeder::class);

        $admin = ModelFactory::createUser(UserRole::ADMINISTRATOR);
        $user = ModelFactory::createUser(UserRole::MEMBER);

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

        $user = ModelFactory::createUser(UserRole::MEMBER);
        $anotherUser = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)
            ->getJson(route('users.show', $anotherUser->{$anotherUser->getRouteKeyName()}));

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

        $user = ModelFactory::createUser(UserRole::MEMBER);

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

        $user = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)->getJson(route('users.show', 1));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
