<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vente;
use Illuminate\Auth\Access\Response;

class VentePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir les ventes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vente $vente): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir une vente
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent créer une vente
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vente $vente): bool
    {
        // Admin peut tout modifier, autres utilisateurs uniquement leurs ventes
        return $user->role->nom === 'Admin' || $vente->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vente $vente): bool
    {
        // Seulement l'admin peut supprimer
        return $user->role->nom === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vente $vente): bool
    {
        return $user->role->nom === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vente $vente): bool
    {
        return $user->role->nom === 'Admin';
    }
}
