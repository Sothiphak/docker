<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    // Determine whether the user can view the category.
    public function view(User $user, Category $category): bool
    {
        // 1. Admin can view EVERYTHING
        if ($user->hasRole('admin')) {
            return true;
        }

        // 2. Manager: Example rule from your instructions
        // (Assuming you want Managers to view everything for this lab, or implement specific logic)
        if ($user->hasRole('manager')) {
            return true;
        }

        // 3. Staff: Can ONLY view if assigned to them
        return $user->id === $category->assigned_to;
    }

    // Determine whether the user can update the category status.
    public function update(User $user, Category $category): bool
    {
        // Admin/Manager override
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }

        // Staff rule
        return $user->hasRole('staff') && $user->id === $category->assigned_to;
    }
}
