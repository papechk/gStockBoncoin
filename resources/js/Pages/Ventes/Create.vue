<template>
    <AppLayoutWithSidebar page-title="Nouvelle Vente">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Point de Vente
                    </h1>
                    <p class="mt-2 text-gray-600">Créez une nouvelle vente rapidement</p>
                </div>
                <Link :href="route('ventes.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Liste des produits -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                    <div class="p-6">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rechercher un produit</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Rechercher par nom ou code-barre..."
                                    class="w-full pl-10 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto">
                            <div
                                v-for="produit in filteredProduits"
                                :key="produit.id"
                                @click="ajouterProduit(produit)"
                                class="border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-indigo-500 hover:shadow-lg transform hover:scale-105 transition-all duration-200 bg-gradient-to-br from-white to-gray-50"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ produit.nom }}</h4>
                                        <p class="text-sm text-gray-500">{{ produit.categorie?.nom }}</p>
                                        <p class="text-sm text-gray-400 mt-1">Stock: {{ produit.quantite_stock }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-indigo-600">{{ formatMoney(produit.prix_vente) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panier -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sticky top-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Panier</h3>

                        <!-- Client -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Client (optionnel)</label>
                            <select v-model="form.client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option :value="null">Client anonyme</option>
                                <option v-for="client in clients" :key="client.id" :value="client.id">
                                    {{ client.nom }}
                                </option>
                            </select>
                        </div>

                        <!-- Articles -->
                        <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                            <div v-for="(item, index) in panier" :key="index" class="flex items-center justify-between border-b border-gray-200 pb-2">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ item.nom }}</p>
                                    <div class="flex items-center mt-1">
                                        <button @click="diminuerQuantite(index)" class="px-2 py-1 bg-gray-200 rounded-l-md hover:bg-gray-300">
                                            -
                                        </button>
                                        <input
                                            v-model.number="item.quantite"
                                            type="number"
                                            min="1"
                                            :max="item.quantite_stock"
                                            class="w-16 px-2 py-1 text-center border-t border-b border-gray-300"
                                        >
                                        <button @click="augmenterQuantite(index)" class="px-2 py-1 bg-gray-200 rounded-r-md hover:bg-gray-300">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="text-right ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ formatMoney(item.prix_unitaire * item.quantite) }}</p>
                                    <button @click="retirerProduit(index)" class="text-xs text-red-600 hover:text-red-800">
                                        Retirer
                                    </button>
                                </div>
                            </div>
                            <div v-if="panier.length === 0" class="text-center text-gray-500 py-8">
                                Panier vide
                            </div>
                        </div>

                        <!-- Totaux -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <p>Total</p>
                                <p>{{ formatMoney(total) }}</p>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mode de paiement</label>
                                <select v-model="form.mode_paiement" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="especes">Espèces</option>
                                    <option value="carte">Carte bancaire</option>
                                    <option value="mobile">Mobile Money</option>
                                    <option value="credit">Crédit</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Montant payé</label>
                                <input
                                    v-model.number="form.montant_paye"
                                    type="number"
                                    step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>

                            <div v-if="monnaie > 0" class="bg-green-50 p-3 rounded-md">
                                <p class="text-sm font-medium text-green-800">Monnaie à rendre</p>
                                <p class="text-2xl font-bold text-green-600">{{ formatMoney(monnaie) }}</p>
                            </div>

                            <div v-if="errors && errors.length > 0" class="bg-red-50 p-3 rounded-md">
                                <p class="text-sm text-red-800" v-for="error in errors" :key="error">{{ error }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 space-y-2">
                            <button
                                @click="validerVente"
                                :disabled="panier.length === 0 || processing"
                                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-medium"
                            >
                                {{ processing ? 'En cours...' : 'Valider la vente' }}
                            </button>
                            <button
                                @click="viderPanier"
                                class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300"
                            >
                                Vider le panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutWithSidebar>
</template>

<script setup>
import AppLayoutWithSidebar from '@/Layouts/AppLayoutWithSidebar.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    produits: Array,
    clients: Array
});

const searchQuery = ref('');
const panier = ref([]);
const processing = ref(false);
const errors = ref([]);

const form = ref({
    client_id: null,
    mode_paiement: 'especes',
    montant_paye: 0,
    statut: 'termine'
});

const filteredProduits = computed(() => {
    if (!searchQuery.value) return props.produits;

    const query = searchQuery.value.toLowerCase();
    return props.produits.filter(p =>
        p.nom.toLowerCase().includes(query) ||
        p.code_barre?.toLowerCase().includes(query)
    );
});

const total = computed(() => {
    return panier.value.reduce((sum, item) => sum + (item.prix_unitaire * item.quantite), 0);
});

const monnaie = computed(() => {
    return Math.max(0, form.value.montant_paye - total.value);
});

const ajouterProduit = (produit) => {
    const existe = panier.value.find(item => item.produit_id === produit.id);

    if (existe) {
        if (existe.quantite < produit.quantite_stock) {
            existe.quantite++;
        }
    } else {
        panier.value.push({
            produit_id: produit.id,
            nom: produit.nom,
            quantite: 1,
            quantite_stock: produit.quantite_stock,
            prix_unitaire: produit.prix_vente,
            sous_total: produit.prix_vente
        });
    }

    // Auto-ajuster le montant payé
    if (form.value.montant_paye === 0) {
        form.value.montant_paye = total.value;
    }
};

const retirerProduit = (index) => {
    panier.value.splice(index, 1);
    if (panier.value.length === 0) {
        form.value.montant_paye = 0;
    }
};

const augmenterQuantite = (index) => {
    const item = panier.value[index];
    if (item.quantite < item.quantite_stock) {
        item.quantite++;
        form.value.montant_paye = total.value;
    }
};

const diminuerQuantite = (index) => {
    const item = panier.value[index];
    if (item.quantite > 1) {
        item.quantite--;
        form.value.montant_paye = total.value;
    } else {
        retirerProduit(index);
    }
};

const viderPanier = () => {
    panier.value = [];
    form.value.montant_paye = 0;
    errors.value = [];
};

const validerVente = () => {
    errors.value = [];

    if (panier.value.length === 0) {
        errors.value.push('Le panier est vide');
        return;
    }

    if (form.value.montant_paye < total.value) {
        errors.value.push('Le montant payé est insuffisant');
        return;
    }

    processing.value = true;

    const data = {
        ...form.value,
        total: total.value,
        montant_rendu: monnaie.value,
        produits: panier.value.map(item => ({
            produit_id: item.produit_id,
            quantite: item.quantite,
            prix_unitaire: item.prix_unitaire,
            sous_total: item.prix_unitaire * item.quantite
        }))
    };

    router.post(route('ventes.store'), data, {
        onSuccess: () => {
            viderPanier();
            processing.value = false;
        },
        onError: (errs) => {
            errors.value = Object.values(errs).flat();
            processing.value = false;
        }
    });
};

const formatMoney = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};
</script>
