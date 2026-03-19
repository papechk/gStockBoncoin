<template>
    <AppLayoutWithSidebar page-title="Nouveau Produit">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nouveau Produit
                    </h1>
                    <p class="mt-2 text-gray-600">Ajoutez un nouveau produit à votre catalogue</p>
                </div>
                <Link :href="route('produits.index')" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </Link>
            </div>
        </div>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="p-8 space-y-6">
                        <!-- Nom -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Nom du produit *
                            </label>
                            <input
                                v-model="form.nom"
                                type="text"
                                required
                                class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            >
                            <span v-if="form.errors.nom" class="text-sm text-red-600 mt-1">{{ form.errors.nom }}</span>
                        </div>

                        <!-- Code barre -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Code-barre</label>
                            <input
                                v-model="form.code_barre"
                                type="text"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <span v-if="form.errors.code_barre" class="text-sm text-red-600">{{ form.errors.code_barre }}</span>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catégorie *</label>
                            <select
                                v-model="form.categorie_id"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Sélectionner une catégorie</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.nom }}
                                </option>
                            </select>
                            <span v-if="form.errors.categorie_id" class="text-sm text-red-600">{{ form.errors.categorie_id }}</span>
                        </div>

                        <!-- Prix -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prix d'achat *</label>
                                <input
                                    v-model.number="form.prix_achat"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <span v-if="form.errors.prix_achat" class="text-sm text-red-600">{{ form.errors.prix_achat }}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prix de vente *</label>
                                <input
                                    v-model.number="form.prix_vente"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <span v-if="form.errors.prix_vente" class="text-sm text-red-600">{{ form.errors.prix_vente }}</span>
                            </div>
                        </div>

                        <!-- Marge calculée -->
                        <div v-if="form.prix_achat && form.prix_vente" class="bg-indigo-50 p-3 rounded-md">
                            <p class="text-sm text-indigo-800">
                                Marge: {{ formatMoney(form.prix_vente - form.prix_achat) }}
                                ({{ calculerPourcentageMarge() }}%)
                            </p>
                        </div>

                        <!-- Stock -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantité en stock *</label>
                                <input
                                    v-model.number="form.quantite_stock"
                                    type="number"
                                    min="0"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <span v-if="form.errors.quantite_stock" class="text-sm text-red-600">{{ form.errors.quantite_stock }}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Seuil d'alerte *</label>
                                <input
                                    v-model.number="form.seuil_alerte"
                                    type="number"
                                    min="0"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <span v-if="form.errors.seuil_alerte" class="text-sm text-red-600">{{ form.errors.seuil_alerte }}</span>
                            </div>
                        </div>

                        <!-- Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image du produit</label>
                            <input
                                @change="handleImageUpload"
                                type="file"
                                accept="image/*"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <span v-if="form.errors.image" class="text-sm text-red-600">{{ form.errors.image }}</span>

                            <!-- Aperçu de l'image -->
                            <div v-if="imagePreview" class="mt-3">
                                <img :src="imagePreview" alt="Aperçu" class="h-32 w-32 object-cover rounded">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-sm text-red-600">{{ form.errors.description }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <a
                            :href="route('produits.index')"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Annuler
                        </a>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400"
                        >
                            {{ form.processing ? 'Création...' : 'Créer le produit' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayoutWithSidebar>
</template>

<script setup>
import AppLayoutWithSidebar from '@/Layouts/AppLayoutWithSidebar.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array
});

const form = useForm({
    nom: '',
    code_barre: '',
    categorie_id: '',
    prix_achat: 0,
    prix_vente: 0,
    quantite_stock: 0,
    seuil_alerte: 5,
    image: null,
    description: ''
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const calculerPourcentageMarge = () => {
    if (form.prix_achat > 0) {
        return ((form.prix_vente - form.prix_achat) / form.prix_achat * 100).toFixed(2);
    }
    return 0;
};

const formatMoney = (value) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value);
};

const submit = () => {
    form.post(route('produits.store'));
};
</script>
