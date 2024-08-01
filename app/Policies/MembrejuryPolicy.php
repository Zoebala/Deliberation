<?php

namespace App\Policies;

use App\Models\Membrejury;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MembrejuryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Membrejuries");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Membrejury $membrejury): bool
    {
        //
        return $user->hasPermissionTo("View Membrejuries");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Membrejuries");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Membrejury $membrejury): bool
    {
        //
        return $user->hasPermissionTo("Update Membrejuries");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Membrejury $membrejury): bool
    {
        //
        return $user->hasPermissionTo("Delete Membrejuries");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Membrejuries");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Membrejury $membrejury): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Membrejury $membrejury): bool
    {
        //
    }
}
