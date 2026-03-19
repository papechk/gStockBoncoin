<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * @param  mixed ...$roles  rôles autorisés (admin, gestionaire_stock, caissier)
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Non connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Sécurité : rôle manquant ou inactif
        if (
            !$user->role ||
            $user->role->etat != 1
        ) {
            abort(403, 'Rôle invalide');
        }

        // Vérification du rôle
        $roleUser = strtolower($user->role->nom);
        $rolesAutorises = array_map('strtolower', $roles);

        // Rôle non autorisé
        if (!in_array($roleUser, $rolesAutorises)) {
            abort(403, 'Accès refusé');
        }

        return $next($request);
    }
}
