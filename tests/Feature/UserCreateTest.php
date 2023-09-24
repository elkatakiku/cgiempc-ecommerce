<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_create_user(): void
    {
        $response = $this->postJson(route('users.store'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_non_admin_users_cannot_create_user(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = User::factory()->create([
            'role_id' => UserRole::STAFF->value,
        ]);

        $user->roles()->attach([UserRole::MEMBER->value, UserRole::STAFF->value]);

        $response = $this->actingAs($user)->postJson(route('users.store'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_user_returns_success_with_valid_data(): void
    {
        $this->seed([RoleSeeder::class,]);

        $response = $this->actingAs(ModelFactory::createUser(UserRole::ADMINISTRATOR))
            ->postJson(route('users.store'), [
                'name' => fake()->name,
                'username' => fake()->unique()->userName,
                'email' => fake()->safeEmail,
                'password' => fake()->password(8),
                'roles' => [3],
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_create_user_returns_error_with_existing_email(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = User::factory()->create([
            'email' => 'existing@email.com',
        ]);

        $response = $this->actingAs(ModelFactory::createUser(UserRole::ADMINISTRATOR))
            ->postJson(route('users.store'), [
                'name' => fake()->name,
                'email' => $user->email,
                'password' => fake()->password,
                'roles' => [3],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_create_user_returns_error_with_invalid_email(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = [
            'name' => fake()->name,
            'email' => 'email.com',
            'password' => fake()->password,
            'roles' => [3],
        ];

        $auth = $this->actingAs(ModelFactory::createUser(UserRole::ADMINISTRATOR));

        $response = $auth->postJson(route('users.store'), $user);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $user['email'] = 'email@.com';
        $response = $auth->postJson(route('users.store'), $user);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_create_user_returns_error_with_invalid_password(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = [
            'name' => fake()->name,
            'email' => 'email.com',
            'password' => 'Password123',
            'roles' => [3],
        ];

        $auth = $this->actingAs(ModelFactory::createUser(UserRole::ADMINISTRATOR));

        $response = $auth->postJson(route('users.store'), $user);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $user['password'] = '12345678';
        $response = $auth->postJson(route('users.store'), $user);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $user['email'] = 'asdasdasd';
        $response = $auth->postJson(route('users.store'), $user);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
