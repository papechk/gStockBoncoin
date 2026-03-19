<template>
    <AppLayoutWithSidebar page-title="Détails Produit">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Détails du produit
                </h2>
                <div class="space-x-2">
                    <a
                        :href="route('produits.edit', produit.id)"
                        class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 font-medium"
                    >
                        Modifier
                    </a>
                    <a
                        :href="route('produits.index')"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 font-medium"
                    >
                        Retour
                    </a>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations du produit -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-start space-x-6">
                            <!-- Image -->
                            <div class="flex-shrink-0">
                                <img
                                    v-if="produit.image"
                                    :src="`/storage/${produit.image}`"
                                    :alt="produit.nom"
                                    class="h-48 w-48 object-cover rounded-lg"
                                >
                                <div v-else class="h-48 w-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ produit.nom }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Code-barre: {{ produit.code_barre || 'N/A' }}</p>

                                <div class="mt-4 space-y-3">
                                    <div>
                                        <span class="text-sm text-gray-600">Catégorie:</span>
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ produit.categorie?.nom || 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Prix d'achat</p>
                                            <p class="text-lg font-semibold text-gray-900">{{ formatMoney(produit.prix_achat) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Prix de vente</p>
                                            <p class="text-lg font-semibold text-green-600">{{ formatMoney(produit.prix_vente) }}</p>
                                        </div>
                                    </div>

                                    <div class="bg-indigo-50 p-3 rounded-md">
                                        <p class="text-sm text-indigo-800">
                                            Marge: {{ formatMoney(produit.prix_vente - produit.prix_achat) }}
                                            ({{ calculerPourcentageMarge() }}%)
                                        </p>
                                    </div>

                                    <div v-if="produit.description" class="mt-4">
                                        <p class="text-sm text-gray-600">Description:</p>
                                        <p class="text-gray-900 mt-1">{{ produit.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique des mouvements -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des mouvements de stock</h3>

                        <div v-if="produit.mvt_stocks && produit.mvt_stocks.length > 0" class="space-y-3">
                            <div
                                v-for="mvt in produit.mvt_stocks"
                                :key="mvt.id"
                                class="border-l-4 pl-4 py-2"
                                :class="mvt.type === 'entree' ? 'border-green-500' : 'border-red-500'"
                            >
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium" :class="mvt.type === 'entree' ? 'text-green-600' : 'text-red-600'">
                                            {{ mvt.type === 'entree' ? '+' : '-' }}{{ mvt.quantite }} unités
                                        </p>
                                        <p class="text-sm text-gray-600">{{ mvt.motif }}</p>
                                        <p class="text-xs text-gray-500">Par: {{ mvt.user?.name || 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">{{ formatDate(mvt.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 py-6">
                            Aucun mouvement de stock
                        </div>
                    </div>
                </div>

                <!-- Ventes récentes -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ventes récentes</h3>

                        <div v-if="produit.vente_details && produit.vente_details.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vente</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qté</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="detail in produit.vente_details.slice(0, 10)" :key="detail.id">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ detail.vente?.numero_vente }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ formatDate(detail.vente?.created_at) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ detail.quantite }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ formatMoney(detail.sous_total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center text-gray-500 py-6">
                            Aucune vente enregistrée
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Stats -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sticky top-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Stock</h3>

                        <div class="text-center mb-4">
                            <p class="text-4xl font-bold" :class="getStockClass()">
                                {{ produit.quantite_stock }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">unités disponibles</p>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Seuil d'alerte</span>
                                <span class="text-sm font-medium text-gray-900">{{ produit.seuil_alerte }}</span>
                            </div>

                            <div v-if="produit.quantite_stock <= produit.seuil_alerte" class="bg-red-50 border-l-4 border-red-400 p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700 font-medium">
                                            Stock faible !
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="mt-6 border-t border-gray-200 pt-4 space-y-3">
                            <h4 class="font-medium text-gray-900">Statistiques</h4>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total vendu</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ calculerTotalVendu() }} unités
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">CA généré</span>
                                <span class="text-sm font-medium text-green-600">
                                    {{ formatMoney(calculerCAGenere()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutWithSidebar>
</template>

<script setup>
import AppLayoutWithSidebar from '@/Layouts/AppLayoutWithSidebar.vue';

const props = defineProps({
    produit: Object
});

const formatMoney = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR');
};

const calculerPourcentageMarge = () => {
    if (props.produit.prix_achat > 0) {
        return ((props.produit.prix_vente - props.produit.prix_achat) / props.produit.prix_achat * 100).toFixed(2);
    }
    return 0;
};

const getStockClass = () => {
    if (props.produit.quantite_stock <= props.produit.seuil_alerte) {
        return 'text-red-600';
    } else if (props.produit.quantite_stock <= props.produit.seuil_alerte * 2) {
        return 'text-yellow-600';
    }
    return 'text-green-600';
};

const calculerTotalVendu = () => {
    if (!props.produit.vente_details) return 0;
    return props.produit.vente_details.reduce((sum, detail) => sum + detail.quantite, 0);
};

const calculerCAGenere = () => {
    if (!props.produit.vente_details) return 0;
    return props.produit.vente_details.reduce((sum, detail) => sum + detail.sous_total, 0);
};
</script>
