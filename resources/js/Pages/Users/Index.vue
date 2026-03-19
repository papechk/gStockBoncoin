<template>
    <AppLayoutWithSidebar page-title="Utilisateurs">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Gestion des Utilisateurs
                    </h1>
                    <p class="mt-2 text-gray-600">Gérez les accès et les rôles de vos utilisateurs</p>
                </div>
                <button @click="showCreateModal = true" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouvel utilisateur
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nom
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Boutique</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg w-10 h-10 flex items-center justify-center text-white font-bold mr-3">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ user.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ user.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getRoleBadgeClass(user.role?.nom)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ user.role?.nom }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.boutique?.nom || 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editUser(user)" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</button>
                                <button v-if="user.id !== $page.props.auth.user.id" @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Affichage de {{ users.from }} à {{ users.to }} sur {{ users.total }} utilisateurs
                    </div>
                    <div class="flex space-x-2">
                        <a v-for="link in users.links" :key="link.label" :href="link.url" v-html="link.label"
                            :class="['px-3 py-2 border rounded-md', link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50']">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showCreateModal || editingUser" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" @click.self="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-medium mb-4">{{ editingUser ? 'Modifier' : 'Nouvel' }} utilisateur</h3>
                <form @submit.prevent="submitForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email *</label>
                            <input v-model="form.email" type="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div v-if="!editingUser">
                            <label class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                            <input v-model="form.password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rôle *</label>
                            <select v-model="form.role_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">Sélectionner un rôle</option>
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.nom }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Boutique</label>
                            <select v-model="form.boutique_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option :value="null">Aucune boutique</option>
                                <option v-for="boutique in boutiques" :key="boutique.id" :value="boutique.id">{{ boutique.nom }}</option>
                            </select>
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
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    users: Object,
    roles: Array,
    boutiques: Array
});

const showCreateModal = ref(false);
const editingUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role_id: '',
    boutique_id: null
});

const editUser = (user) => {
    editingUser.value = user;
    form.name = user.name;
    form.email = user.email;
    form.role_id = user.role_id;
    form.boutique_id = user.boutique_id;
};

const closeModal = () => {
    showCreateModal.value = false;
    editingUser.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingUser.value) {
        form.put(route('users.update', editingUser.value.id), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const deleteUser = (user) => {
    if (confirm(`Supprimer "${user.name}" ?`)) {
        router.delete(route('users.destroy', user.id));
    }
};

const getRoleBadgeClass = (role) => {
    const classes = {
        'admin': 'bg-purple-100 text-purple-800',
        'caissier': 'bg-blue-100 text-blue-800',
        'gestionnaire_stock': 'bg-green-100 text-green-800'
    };
    return classes[role] || 'bg-gray-100 text-gray-800';
};
</script>
