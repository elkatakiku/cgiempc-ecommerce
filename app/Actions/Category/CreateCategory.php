<?php

namespace App\Actions\Category;

use App\Models\Category;

class CreateCategory
{
    public function handle(string $name): Category
    {
        return Category::create([
            'name' => $name,
        ]);
    }
}
