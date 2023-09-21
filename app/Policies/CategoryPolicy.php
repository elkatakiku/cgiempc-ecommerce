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
        Log::info(__METHOD__);
        Log::info($user->name);
        Log::info($user->role->name);
        return $user->role->isAdmin();
    }

    public function update(User $user, Category $category): bool
    {
        Log::info(__METHOD__);
        Log::info($user->name);
        Log::info($user->role->name);
        return $user->role->isAdmin();
    }

    public function delete(User $user, Category $category): bool
    {
        Log::info(__METHOD__);
        Log::info($user->name);
        Log::info($user->role->name);
        return $user->role->isAdmin();
    }
}
