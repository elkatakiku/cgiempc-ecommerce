<?php

namespace Tests\Feature\Category;

use App\Enums\UserRole;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\ModelFactory;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_update_category(): void
    {
        $this->seed(RoleSeeder::class);

        $response = $this->putJson(route(
            'categories.update',
            'id'
        ));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_only_admin_can_update_category(): void
    {
        $this->seed([
            RoleSeeder::class,
            CategorySeeder::class,
        ]);

        $category = Category::inRandomOrder()->first();
        $admin = ModelFactory::createUser(UserRole::ADMINISTRATOR);

        $response = $this->actingAs($admin)
            ->putJson(route('categories.update', $category->slug), [
                'name' => 'Food',
            ]);
        $response->assertStatus(Response::HTTP_OK);


        $category = $category->fresh();

        $otherUser = ModelFactory::createUser(UserRole::STAFF, [UserRole::MEMBER->value, UserRole::STAFF->value]);
        $response = $this->actingAs($otherUser)
            ->putJson(route('categories.update', $category->slug), [
                'name' => 'Rice',
            ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
