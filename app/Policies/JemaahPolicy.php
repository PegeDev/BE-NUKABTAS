<?php

namespace App\Policies;

use App\Models\Jemaah;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JemaahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("View Jemaah")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Jemaah $jemaah): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("View Jemaah")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Create Jemaah")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jemaah $jemaah): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Update Jemaah")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Jemaah $jemaah): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Delete Jemaah")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Jemaah $jemaah): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Jemaah $jemaah): bool
    {
        //
    }
}
