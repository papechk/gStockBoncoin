<template>
    <AppLayoutWithSidebar page-title="Mouvements de Stock">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                        Mouvements de Stock
                    </h1>
                    <p class="mt-2 text-gray-600">Historique des entrées et sorties de stock</p>
                </div>
                <button @click="showCreateModal = true" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau mouvement
                </button>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
            <div class="p-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Date
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Motif</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Utilisateur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="mvt in mouvements.data" :key="mvt.id" class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(mvt.created_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ mvt.produit?.nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="mvt.type === 'entree' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800' : 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800'"
                                    class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ mvt.type === 'entree' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="mvt.type === 'entree' ? 'text-green-600' : 'text-red-600'">
                                {{ mvt.type === 'entree' ? '+' : '-' }}{{ mvt.quantite }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ mvt.motif }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ mvt.user?.name }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Affichage de {{ mouvements.from }} à {{ mouvements.to }} sur {{ mouvements.total }} mouvements
                    </div>
                    <div class="flex space-x-2">
                        <a v-for="link in mouvements.links" :key="link.label" :href="link.url" v-html="link.label"
                            :class="['px-3 py-2 border rounded-md', link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50']">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-medium mb-4">Nouveau mouvement de stock</h3>
                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Produit *</label>
                            <select v-model="form.produit_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Sélectionner un produit</option>
                                <option v-for="prod in produits" :key="prod.id" :value="prod.id">
                                    {{ prod.nom }} (Stock: {{ prod.quantite_stock }})
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type *</label>
                            <select v-model="form.type" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="entree">Entrée</option>
                                <option value="sortie">Sortie</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantité *</label>
                            <input v-model.number="form.quantite" type="number" min="1" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Motif *</label>
                            <textarea v-model="form.motif" rows="2" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayoutWithSidebar>
</template>

<script setup>
import AppLayoutWithSidebar from '@/Layouts/AppLayoutWithSidebar.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    mouvements: Object,
    produits: Array
});

const showCreateModal = ref(false);

const form = useForm({
    produit_id: '',
    type: 'entree',
    quantite: 1,
    motif: ''
});

const closeModal = () => {
    showCreateModal.value = false;
    form.reset();
};

const submitForm = () => {
    form.post(route('mvtstock.store'), {
        onSuccess: () => closeModal()
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('fr-FR');
};
</script>
