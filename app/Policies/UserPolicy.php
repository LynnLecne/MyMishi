<?php

namespace App\Policies;

use App\Models\User;

    class UserPolicy
{
    /**
     * Todos os utilizadores autenticados podem ver a lista.
     */
    public function viewAny(User $authUser): bool
    {
        return true;
    }

    /**
     * Staff vê qualquer perfil; 'user' comum só vê o próprio.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        return $authUser->isStaff() || $authUser->id === $targetUser->id;
    }

    /**
     * Apenas Staff (mod/admin) pode criar novos utilizadores.
     */
    public function create(User $authUser): bool
    {
        return $authUser->isStaff();
    }

    /**
     * Staff edita outros perfis; 'user' comum edita apenas o próprio perfil.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        if ($authUser->isStaff()) {
            // Opcional: impedir que um 'mod' edite um 'admin'
            if ($authUser->isMod() && $targetUser->isAdmin()) {
                return false;
            }
            return true;
        }

        return $authUser->id === $targetUser->id;
    }

    /**
     * Apenas admin pode apagar utilizadores (e nunca a si próprio).
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        return $authUser->isAdmin() && $authUser->id !== $targetUser->id;
    }
}

