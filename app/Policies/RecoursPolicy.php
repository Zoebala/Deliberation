<?php

namespace App\Policies;

use App\Models\Recours;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecoursPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Recours");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recours $recours): bool
    {
        //
        return $user->hasPermissionTo("View Recours");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Recours");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recours $recours): bool
    {
        //
        return $user->hasPermissionTo("Update Recours");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recours $recours): bool
    {
        //
        return $user->hasPermissionTo("Delete Recours");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Recours");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recours $recours): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recours $recours): bool
    {
        //
    }
}
