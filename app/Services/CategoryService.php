<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function storeCategory(string $name): Category
    {
        return Category::create(['name' => $name]);
    }
}
