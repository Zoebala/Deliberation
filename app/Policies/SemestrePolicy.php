<?php

namespace App\Policies;

use App\Models\Semestre;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SemestrePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("ViewAny Semestres");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Semestre $semestre): bool
    {
        //
        return $user->hasPermissionTo("View Semestres");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->hasPermissionTo("Create Semestres");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Semestre $semestre): bool
    {
        //
        return $user->hasPermissionTo("Update Semestres");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Semestre $semestre): bool
    {
        //
        return $user->hasPermissionTo("Delete Semestres");
    }
    public function deleteAny(User $user): bool
    {
        //
        return $user->hasPermissionTo("DeleteAny Semestres");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Semestre $semestre): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Semestre $semestre): bool
    {
        //
    }
}
