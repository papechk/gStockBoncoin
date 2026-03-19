<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produit;
use App\Models\Client;
use App\Models\VenteDetail;
use App\Http\Requests\StoreVenteRequest;
use App\Http\Requests\UpdateVenteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventes = Vente::with(['client', 'user', 'boutique'])
            ->orderBy('date_vente', 'desc')
            ->paginate(20);

        return \Inertia\Inertia::render('Ventes/Index', [
            'ventes' => $ventes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produits = Produit::where('quantite_stock', '>', 0)
            ->with('categorie')
            ->get();
        $clients = Client::all();

        return \Inertia\Inertia::render('Ventes/Create', [
            'produits' => $produits,
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVenteRequest $request)
    {
        try {
            DB::beginTransaction();

            // Créer la vente
            $vente = Vente::create([
                'boutique_id' => Auth::user()->boutique_id,
                'user_id' => Auth::id(),
                'client_id' => $request->client_id,
                'numero_vente' => 'VTE-' . date('YmdHis'),
                'date_vente' => now(),
                'total' => $request->total,
                'montant_paye' => $request->montant_paye,
                'montant_rendu' => $request->montant_rendu,
                'statut' => $request->statut ?? 'termine',
                'mode_paiement' => $request->mode_paiement
            ]);

            // Ajouter les lignes de vente et mettre à jour le stock
            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['produit_id']);

                // Vérifier le stock
                if ($produit->quantite_stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}");
                }

                // Créer la ligne de vente
                VenteDetail::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $item['produit_id'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'sous_total' => $item['sous_total']
                ]);

                // Décrémenter le stock
                $produit->decrement('quantite_stock', $item['quantite']);
            }

            DB::commit();

            return redirect()->route('ventes.show', $vente)
                ->with('success', 'Vente enregistrée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        $vente->load(['venteDetails.produit', 'client', 'user', 'boutique']);

        return \Inertia\Inertia::render('Ventes/Show', [
            'vente' => $vente
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        if ($vente->statut === 'annule') {
            return back()->withErrors(['error' => 'Impossible de modifier une vente annulée']);
        }

        $produits = Produit::with('categorie')->get();
        $clients = Client::all();

        return view('ventes.edit', compact('vente', 'produits', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVenteRequest $request, Vente $vente)
    {
        if ($vente->statut === 'annule') {
            return back()->withErrors(['error' => 'Impossible de modifier une vente annulée']);
        }

        $vente->update($request->validated());

        return redirect()->route('ventes.show', $vente)
            ->with('success', 'Vente modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        try {
            DB::beginTransaction();

            // Restaurer le stock avant suppression
            foreach ($vente->venteDetails as $detail) {
                $detail->produit->increment('quantite_stock', $detail->quantite);
            }

            $vente->delete();

            DB::commit();

            return redirect()->route('ventes.index')
                ->with('success', 'Vente supprimée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }
}
