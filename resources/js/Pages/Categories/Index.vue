<template>
    <AppLayoutWithSidebar page-title="Catégories">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Catégories de Produits
                    </h1>
                    <p class="mt-2 text-gray-600">Organisez vos produits par catégories</p>
                </div>
                <button @click="showCreateModal = true" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvelle catégorie
                </button>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="cat in categories" :key="cat.id" class="border-2 border-gray-200 rounded-2xl p-6 hover:border-indigo-500 hover:shadow-lg transition-all duration-200 transform hover:scale-105 bg-gradient-to-br from-white to-gray-50">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl p-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="editCategorie(cat)" class="p-2 bg-amber-100 text-amber-600 hover:bg-amber-200 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="deleteCategorie(cat)" class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ cat.nom }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ cat.description || 'Aucune description' }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span class="text-sm font-bold text-gray-900">{{ cat.produits_count }}</span>
                                    <span class="text-xs text-gray-500 ml-1">produits</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                    Actif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Create/Edit -->
        <div v-if="showCreateModal || editingCategorie" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-medium mb-4">{{ editingCategorie ? 'Modifier' : 'Nouvelle' }} catégorie</h3>
                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input v-model="form.nom" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                            <span v-if="form.errors.nom" class="text-sm text-red-600">{{ form.errors.nom }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:bg-gray-400">
                            {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
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
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array
});

const showCreateModal = ref(false);
const editingCategorie = ref(null);

const form = useForm({
    nom: '',
    description: ''
});

const editCategorie = (cat) => {
    editingCategorie.value = cat;
    form.nom = cat.nom;
    form.description = cat.description;
};

const closeModal = () => {
    showCreateModal.value = false;
    editingCategorie.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingCategorie.value) {
        form.put(route('categories.update', editingCategorie.value.id), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('categories.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const deleteCategorie = (cat) => {
    if (confirm(`Supprimer "${cat.nom}" ?`)) {
        router.delete(route('categories.destroy', cat.id));
    }
};
</script>
