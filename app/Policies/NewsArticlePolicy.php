<?php

namespace App\Policies;

use App\Models\NewsArticle;
use App\Models\User;

class NewsArticlePolicy
{
    /**
     * Determine whether the user can view the news desk.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view a news note.
     */
    public function view(User $user, NewsArticle $newsArticle): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can publish a news note.
     */
    public function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a news note.
     */
    public function update(User $user, NewsArticle $newsArticle): bool
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can remove a news note.
     */
    public function delete(User $user, NewsArticle $newsArticle): bool
    {
        return $user->isAdministrator();
    }
}
