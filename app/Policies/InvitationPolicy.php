<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use App\Services\GetParticipantStatus;
use App\Services\isStatusHigherThan;

class InvitationPolicy
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
    public function view(User $user, Invitation $invitation): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($project_id): bool
    {
        return isStatusHigherThan::executor($project_id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invitation $invitation): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        return true;
    }
}
