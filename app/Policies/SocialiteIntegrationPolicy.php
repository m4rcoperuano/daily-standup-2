<?php

namespace App\Policies;

use App\Models\SocialiteIntegration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SocialiteIntegrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SocialiteIntegration $socialiteIntegration): bool
    {
        return $user->is($socialiteIntegration->user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SocialiteIntegration $socialiteIntegration): bool
    {
        return $user->is($socialiteIntegration->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SocialiteIntegration $socialiteIntegration): bool
    {
        return $user->is($socialiteIntegration->user);
    }

}
