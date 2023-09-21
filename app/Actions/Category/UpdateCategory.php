<?php

namespace App\Actions\Category;

use App\Models\Category;

class UpdateCategory
{
    public function handle(Category $category, string $name): void
    {
        $category->update(['name' => $name,]);
    }
}
