<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role', 'boutique'])
            ->orderBy('name')
            ->paginate(20);

        $roles = Role::all();
        $boutiques = Boutique::all();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'boutiques' => $boutiques
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('etat', 1)->get();
        $boutiques = Boutique::all();

        return view('users.create', compact('roles', 'boutiques'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'boutique_id' => ['nullable', 'exists:boutiques,id']
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'boutique', 'ventes']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('etat', 1)->get();
        $boutiques = Boutique::all();

        return view('users.edit', compact('user', 'roles', 'boutiques'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'boutique_id' => ['nullable', 'exists:boutiques,id']
        ];

        // Ajouter validation mot de passe si fourni
        if ($request->filled('password')) {
            $rules['password'] = [Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        // Hacher le mot de passe si fourni
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte']);
        }

        // Vérifier les ventes liées
        if ($user->ventes()->count() > 0) {
            return back()->withErrors(['error' => 'Impossible de supprimer un utilisateur ayant effectué des ventes']);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
}
