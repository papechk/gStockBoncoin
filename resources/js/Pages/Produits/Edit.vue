<template>
    <AppLayoutWithSidebar page-title="Éditer Produit">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier le produit
            </h2>
        </template>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="p-6 space-y-6">
                        <!-- Nom -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom du produit *</label>
                            <input
                                v-model="form.nom"
                                type="text"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <span v-if="form.errors.nom" class="text-sm text-red-600">{{ form.errors.nom }}</span>
                        </div>

                        <!-- Code barre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Code-barre</label>
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

                        <!-- Stock (lecture seule, géré via mouvements) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantité en stock</label>
                                <input
                                    :value="produit.quantite_stock"
                                    type="number"
                                    disabled
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed"
                                >
                                <p class="text-xs text-gray-500 mt-1">Modifiable via les mouvements de stock</p>
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

                            <!-- Image actuelle -->
                            <div v-if="produit.image && !imagePreview" class="mt-2 mb-3">
                                <p class="text-sm text-gray-600 mb-2">Image actuelle:</p>
                                <img :src="`/storage/${produit.image}`" alt="Image actuelle" class="h-32 w-32 object-cover rounded">
                            </div>

                            <input
                                @change="handleImageUpload"
                                type="file"
                                accept="image/*"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <span v-if="form.errors.image" class="text-sm text-red-600">{{ form.errors.image }}</span>

                            <!-- Aperçu de la nouvelle image -->
                            <div v-if="imagePreview" class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Nouvelle image:</p>
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
                            {{ form.processing ? 'Modification...' : 'Modifier le produit' }}
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
    produit: Object,
    categories: Array
});

const form = useForm({
    nom: props.produit.nom,
    code_barre: props.produit.code_barre,
    categorie_id: props.produit.categorie_id,
    prix_achat: props.produit.prix_achat,
    prix_vente: props.produit.prix_vente,
    seuil_alerte: props.produit.seuil_alerte,
    image: null,
    description: props.produit.description,
    _method: 'PUT'
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
    form.post(route('produits.update', props.produit.id));
};
</script>
