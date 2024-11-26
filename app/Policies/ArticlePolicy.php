<?php

namespace App\Policies;

use App\Models\Illuminate\Article;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\User;

class ArticlePolicy
{
    public function create(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    public function forceDelete(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
