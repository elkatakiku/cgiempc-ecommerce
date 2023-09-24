<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CategoryPolicy
{
    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role->isAdmin();
    }

    public function update(User $user, Category $category): bool
    {
        return $user->role->isAdmin();
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->role->isAdmin();
    }
}
