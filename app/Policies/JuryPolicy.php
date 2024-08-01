<?php

namespace App\Policies;

use App\Models\Jury;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JuryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Juries");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Jury $jury): bool
    {
        //
        return $user->hasPermissionTo("View Juries");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Juries");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jury $jury): bool
    {
        //
        return $user->hasPermissionTo("Update Juries");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Jury $jury): bool
    {
        //
        return $user->hasPermissionTo("Delete Juries");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Juries");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Jury $jury): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Jury $jury): bool
    {
        //
    }
}
