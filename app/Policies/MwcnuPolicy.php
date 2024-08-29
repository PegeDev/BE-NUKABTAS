<?php

namespace App\Policies;

use App\Models\Mwcnu;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MwcnuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("View MWCNU")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mwcnu $mwcnu): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("View MWCNU")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Create MWCNU")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mwcnu $mwcnu): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Update MWCNU")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mwcnu $mwcnu): bool
    {
        if ($user->hasRole(['dev']) || $user->hasPermissionTo("Delete MWCNU")) {

            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mwcnu $mwcnu): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mwcnu $mwcnu): bool
    {
        //
    }
}
