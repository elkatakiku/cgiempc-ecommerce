<?php

namespace Tests\Feature\Category;

use App\Enums\UserRole;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class ViewCategoryTest extends TestCase
{

    use RefreshDatabase;

    public function test_anyone_can_view_the_category(): void
    {
        $this->seed([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        $category = Category::first();

        $users = [
            ModelFactory::createUser(UserRole::ADMINISTRATOR),
            ModelFactory::createUser(UserRole::STAFF),
            ModelFactory::createUser(UserRole::MEMBER),
        ];

        foreach ($users as $user) {
            $response = $this->actingAs($user)->getJson(route('categories.show', $category->slug));
            $response->assertStatus(Response::HTTP_OK);
        }
    }

    public function test_returns_error_when_category_not_found(): void
    {
        $this->seed(RoleSeeder::class);

        $user = ModelFactory::createUser(UserRole::MEMBER);

        $response = $this->actingAs($user)->getJson(route('categories.show', 'food'));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_returns_error_when_id_used_in_route()
    {
        $this->seed([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        $user = ModelFactory::createUser(UserRole::MEMBER);
        $category = Category::first();
        assert($category);

        $response = $this->actingAs($user)->getJson(route('categories.show', $category->id));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
