<template>
    <AppLayoutWithSidebar page-title="Détails Vente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails de la vente #{{ vente.numero_vente }}
            </h2>
        </template>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- En-tête du ticket -->
                    <div class="text-center mb-6 border-b border-gray-200 pb-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ vente.boutique?.nom || 'Boutique' }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ vente.boutique?.adresse }}</p>
                        <p class="text-sm text-gray-600">{{ vente.boutique?.telephone }}</p>
                    </div>

                    <!-- Informations de la vente -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">N° Vente</p>
                            <p class="font-semibold text-gray-900">{{ vente.numero_vente }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date</p>
                            <p class="font-semibold text-gray-900">{{ formatDate(vente.created_at) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Caissier</p>
                            <p class="font-semibold text-gray-900">{{ vente.user?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Client</p>
                            <p class="font-semibold text-gray-900">{{ vente.client?.nom || 'Client anonyme' }}</p>
                        </div>
                    </div>

                    <!-- Articles -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Articles</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qté</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">P.U.</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="detail in vente.vente_details" :key="detail.id">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ detail.produit?.nom || 'Produit supprimé' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ detail.quantite }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ formatMoney(detail.prix_unitaire) }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">{{ formatMoney(detail.sous_total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totaux -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between py-2">
                            <span class="text-lg font-medium text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">{{ formatMoney(vente.total) }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">Mode de paiement</span>
                            <span class="text-sm font-medium text-gray-900">{{ formatModePaiement(vente.mode_paiement) }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-gray-600">Montant payé</span>
                            <span class="text-sm font-medium text-gray-900">{{ formatMoney(vente.montant_paye) }}</span>
                        </div>
                        <div v-if="vente.montant_rendu > 0" class="flex justify-between py-2 bg-green-50 px-3 rounded-md">
                            <span class="text-sm font-medium text-green-800">Monnaie rendue</span>
                            <span class="text-sm font-bold text-green-600">{{ formatMoney(vente.montant_rendu) }}</span>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="mt-6 text-center">
                        <span :class="getStatutClass(vente.statut)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                            {{ formatStatut(vente.statut) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-center space-x-3">
                        <button
                            @click="imprimer"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium"
                        >
                            Imprimer
                        </button>
                        <a
                            :href="route('ventes.index')"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 font-medium"
                        >
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutWithSidebar>
</template>

<script setup>
import AppLayoutWithSidebar from '@/Layouts/AppLayoutWithSidebar.vue';

const props = defineProps({
    vente: Object
});

const formatMoney = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('fr-FR');
};

const formatModePaiement = (mode) => {
    const modes = {
        'especes': 'Espèces',
        'carte': 'Carte bancaire',
        'mobile': 'Mobile Money',
        'credit': 'Crédit'
    };
    return modes[mode] || mode;
};

const formatStatut = (statut) => {
    const statuts = {
        'en_cours': 'En cours',
        'termine': 'Terminé',
        'annule': 'Annulé'
    };
    return statuts[statut] || statut;
};

const getStatutClass = (statut) => {
    const classes = {
        'en_cours': 'bg-yellow-100 text-yellow-800',
        'termine': 'bg-green-100 text-green-800',
        'annule': 'bg-red-100 text-red-800'
    };
    return classes[statut] || 'bg-gray-100 text-gray-800';
};

const imprimer = () => {
    window.print();
};
</script>

<style>
@media print {
    nav, .no-print {
        display: none;
    }
    .bg-white {
        box-shadow: none;
    }
}
</style>
