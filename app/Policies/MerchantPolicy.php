<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MerchantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin yang bisa melihat semua merchant
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Merchant $merchant): bool
    {
        // Admin bisa melihat semua data merchant, merchant hanya bisa melihat miliknya sendiri
        return $user->role === UserRole::Admin || $user->id === $merchant->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin bisa membuat merchant baru
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        // Admin bisa mengupdate merchant mana pun, merchant hanya bisa mengupdate miliknya sendiri
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        // Hanya admin yang bisa menghapus merchant
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Merchant $merchant): bool
    {
        // Hanya admin yang bisa merestore merchant
        return $user->role === UserRole::Admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Merchant $merchant): bool
    {
        // Hanya admin yang bisa menghapus secara permanen
        return $user->role === UserRole::Admin;
    }
}
