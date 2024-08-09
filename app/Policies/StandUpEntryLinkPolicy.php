<?php

namespace App\Policies;

use App\Models\StandUpEntryLink;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StandUpEntryLinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StandUpEntryLink $standUpEntryLink): bool
    {
        return $user->can('view', $standUpEntryLink->standUpEntry);
    }
}
