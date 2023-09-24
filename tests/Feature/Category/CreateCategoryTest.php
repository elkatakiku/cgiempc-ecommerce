<?php

namespace Tests\Feature\Category;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_create_category(): void
    {
        $response = $this->postJson(route('categories.store'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_non_admin_cannot_create_category(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = ModelFactory::createUser(UserRole::MEMBER, [UserRole::MEMBER->value, UserRole::STAFF->value]);
        $response = $this->actingAs($user)->postJson(route('categories.store'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_create_category_returns_success_with_valid_data(): void
    {
        $this->seed([RoleSeeder::class,]);

        $response = $this->actingAs(ModelFactory::createUser(UserRole::ADMINISTRATOR))
            ->postJson(route('categories.store'), [
                'name' => fake()->words(3, true),
            ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_creating_category_with_same_name_returns_success(): void
    {
        $this->seed([RoleSeeder::class,]);

        $user = ModelFactory::createUser(UserRole::ADMINISTRATOR);

        $response = $this->actingAs($user)
            ->postJson(route('categories.store'), [
                'name' => 'Food',
            ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->actingAs($user)
            ->postJson(route('categories.store'), [
                'name' => 'Food',
            ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->actingAs($user)
            ->getJson(route('categories.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data');
    }
}
