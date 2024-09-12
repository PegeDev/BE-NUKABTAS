<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mwcnu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MwcnuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_mwcnu');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->can('view_mwcnu');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Mwcnu $mwcnu): bool
    {
        return $user->can('create_mwcnu') && $user->id === $mwcnu->admin_id;;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mwcnu $mwcnu): bool
    {
        return $user->can('update_mwcnu') && $user->id === $mwcnu->admin_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mwcnu $mwcnu): bool
    {
        return $user->can('delete_mwcnu') && $user->id === $mwcnu->admin_id;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_mwcnu');
    }

    public function import(User $user, Mwcnu $mwcnu): bool
    {
        return $user->can('import_mwcnu') && $user->id === $mwcnu->admin_id;
    }

    public function manageAdmin(User $user, Mwcnu $mwcnu): bool
    {
        return $user->can('manage_admin_mwcnu') && $user->id === $mwcnu->admin_id;
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user): bool
    {
        return $user->can('{{ Restore }}');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('{{ RestoreAny }}');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }
}
