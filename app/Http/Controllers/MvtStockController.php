<?php

namespace App\Http\Controllers;

use App\Models\MvtStock;
use App\Models\Produit;
use App\Http\Requests\StoreMvtStockRequest;
use App\Http\Requests\UpdateMvtStockRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MvtStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mvtStocks = MvtStock::with(['produit', 'user', 'boutique'])
            ->orderBy('date_mouvement', 'desc')
            ->paginate(20);

        $produits = Produit::all();

        return Inertia::render('Stock/Index', [
            'mouvements' => $mvtStocks,
            'produits' => $produits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produits = Produit::with('categorie')->get();

        return view('mvtstock.create', compact('produits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMvtStockRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['boutique_id'] = Auth::user()->boutique_id;
            $data['date_mouvement'] = now();

            $mvtStock = MvtStock::create($data);

            // Mettre à jour le stock du produit
            $produit = Produit::findOrFail($request->produit_id);

            if ($request->type === 'entree') {
                $produit->increment('quantite_stock', $request->quantite);
            } else {
                // Vérifier stock suffisant
                if ($produit->quantite_stock < $request->quantite) {
                    throw new \Exception('Stock insuffisant');
                }
                $produit->decrement('quantite_stock', $request->quantite);
            }

            DB::commit();

            return redirect()->route('mvtstock.index')
                ->with('success', 'Mouvement de stock enregistré avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MvtStock $mvtStock)
    {
        $mvtStock->load(['produit', 'user', 'boutique']);

        return view('mvtstock.show', compact('mvtStock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MvtStock $mvtStock)
    {
        $produits = Produit::with('categorie')->get();

        return view('mvtstock.edit', compact('mvtStock', 'produits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMvtStockRequest $request, MvtStock $mvtStock)
    {
        $mvtStock->update($request->validated());

        return redirect()->route('mvtstock.index')
            ->with('success', 'Mouvement de stock modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MvtStock $mvtStock)
    {
        try {
            DB::beginTransaction();

            // Restaurer le stock
            $produit = $mvtStock->produit;

            if ($mvtStock->type === 'entree') {
                $produit->decrement('quantite_stock', $mvtStock->quantite);
            } else {
                $produit->increment('quantite_stock', $mvtStock->quantite);
            }

            $mvtStock->delete();

            DB::commit();

            return redirect()->route('mvtstock.index')
                ->with('success', 'Mouvement supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }
}
