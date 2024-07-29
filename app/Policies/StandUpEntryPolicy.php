<?php

namespace App\Policies;

use App\Models\StandUpEntry;
use App\Models\StandUpGroup;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StandUpEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, ?StandUpGroup $standUpGroup = null): bool
    {
        if ($standUpGroup === null) {
            return false;
        }

        return $user->can('view', $standUpGroup);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StandUpEntry $standUpEntry): bool
    {
        return $user->can('view', $standUpEntry->standUpGroup);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?StandUpGroup $standUpGroup = null): bool
    {
        if ($standUpGroup === null) {
            return false;
        }

        return $user->can('view', $standUpGroup);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StandUpEntry $standUpEntry): bool
    {
        return $user->is($standUpEntry->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StandUpEntry $standUpEntry): bool
    {
        return $user->is($standUpEntry->user);
    }
}
