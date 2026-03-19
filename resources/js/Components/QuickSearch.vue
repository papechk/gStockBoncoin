<template>
    <!-- Modal de recherche rapide (Ctrl+K) -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto" @click.self="close">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

                <!-- Modal -->
                <div class="flex min-h-screen items-start justify-center p-4 pt-[10vh]">
                    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl" @click.stop>
                        <!-- Search Input -->
                        <div class="relative border-b border-gray-200">
                            <svg class="absolute left-5 top-5 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input
                                ref="searchInput"
                                v-model="query"
                                type="text"
                                placeholder="Rechercher... (pages, produits, clients)"
                                class="w-full py-5 pl-14 pr-4 text-lg bg-transparent border-0 focus:ring-0 placeholder-gray-400"
                                @keydown.down.prevent="highlightNext"
                                @keydown.up.prevent="highlightPrev"
                                @keydown.enter.prevent="selectHighlighted"
                                @keydown.esc="close"
                            />
                            <div class="absolute right-4 top-4 flex items-center gap-2">
                                <kbd class="px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-300 rounded">ESC</kbd>
                            </div>
                        </div>

                        <!-- Results -->
                        <div v-if="query.length > 0" class="max-h-[60vh] overflow-y-auto p-2">
                            <div v-if="filteredResults.length === 0" class="px-4 py-8 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm">Aucun résultat trouvé</p>
                            </div>

                            <div v-else class="space-y-1">
                                <button
                                    v-for="(result, index) in filteredResults"
                                    :key="result.id"
                                    :class="[
                                        'w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left transition-colors duration-150',
                                        highlightedIndex === index
                                            ? 'bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200'
                                            : 'hover:bg-gray-50 border-2 border-transparent'
                                    ]"
                                    @click="selectResult(result)"
                                    @mouseenter="highlightedIndex = index"
                                >
                                    <div :class="[
                                        'flex items-center justify-center w-10 h-10 rounded-lg',
                                        result.type === 'page' ? 'bg-indigo-100 text-indigo-600' :
                                        result.type === 'produit' ? 'bg-purple-100 text-purple-600' :
                                        'bg-blue-100 text-blue-600'
                                    ]">
                                        <component :is="result.icon" class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-900">{{ result.title }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ result.subtitle }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span :class="[
                                            'px-2 py-1 text-xs font-semibold rounded-full',
                                            result.type === 'page' ? 'bg-indigo-100 text-indigo-700' :
                                            result.type === 'produit' ? 'bg-purple-100 text-purple-700' :
                                            'bg-blue-100 text-blue-700'
                                        ]">
                                            {{ result.badge }}
                                        </span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div v-else class="p-4">
                            <p class="px-2 text-xs font-semibold text-gray-500 uppercase mb-2">Actions rapides</p>
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    v-for="action in quickActions"
                                    :key="action.id"
                                    @click="goTo(action.route)"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 hover:border-indigo-200 border-2 border-transparent transition-all duration-150 text-left"
                                >
                                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-2 rounded-lg">
                                        <component :is="action.icon" class="w-5 h-5 text-white" />
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ action.title }}</div>
                                        <div class="text-xs text-gray-500">{{ action.subtitle }}</div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-t border-gray-200 px-4 py-3 bg-gray-50 rounded-b-2xl">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded">↑</kbd>
                                        <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded">↓</kbd>
                                        pour naviguer
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <kbd class="px-1.5 py-0.5 bg-white border border-gray-300 rounded">↵</kbd>
                                        pour sélectionner
                                    </span>
                                </div>
                                <span>Raccourci: Ctrl+K</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, h } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    isOpen: Boolean
});

const emit = defineEmits(['close']);

const query = ref('');
const highlightedIndex = ref(0);
const searchInput = ref(null);

// Quick Actions
const quickActions = [
    {
        id: 'new-vente',
        title: 'Nouvelle Vente',
        subtitle: 'Point de vente',
        route: 'ventes.create',
        icon: h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' })
        ])
    },
    {
        id: 'new-produit',
        title: 'Nouveau Produit',
        subtitle: 'Ajouter au catalogue',
        route: 'produits.create',
        icon: h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 6v6m0 0v6m0-6h6m-6 0H6' })
        ])
    },
    {
        id: 'dashboard',
        title: 'Dashboard',
        subtitle: 'Vue d\'ensemble',
        route: 'dashboard',
        icon: h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })
        ])
    },
    {
        id: 'clients',
        title: 'Clients',
        subtitle: 'Base de données',
        route: 'clients.index',
        icon: h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
            h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' })
        ])
    }
];

// Searchable items
const searchableItems = computed(() => [
    // Pages
    { id: 'page-dashboard', type: 'page', title: 'Dashboard', subtitle: 'Vue d\'ensemble', badge: 'Page', route: 'dashboard', icon: quickActions[2].icon },
    { id: 'page-ventes', type: 'page', title: 'Ventes', subtitle: 'Transactions et historique', badge: 'Page', route: 'ventes.index', icon: quickActions[0].icon },
    { id: 'page-produits', type: 'page', title: 'Produits', subtitle: 'Catalogue et inventaire', badge: 'Page', route: 'produits.index', icon: quickActions[1].icon },
    { id: 'page-clients', type: 'page', title: 'Clients', subtitle: 'Base de données', badge: 'Page', route: 'clients.index', icon: quickActions[3].icon },
    { id: 'page-categories', type: 'page', title: 'Catégories', subtitle: 'Organisation des produits', badge: 'Page', route: 'categories.index', icon: quickActions[1].icon },
]);

const filteredResults = computed(() => {
    if (!query.value) return [];

    const searchQuery = query.value.toLowerCase();
    return searchableItems.value.filter(item =>
        item.title.toLowerCase().includes(searchQuery) ||
        item.subtitle.toLowerCase().includes(searchQuery)
    ).slice(0, 8);
});

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        setTimeout(() => searchInput.value?.focus(), 100);
        highlightedIndex.value = 0;
    } else {
        query.value = '';
    }
});

watch(filteredResults, () => {
    highlightedIndex.value = 0;
});

function close() {
    emit('close');
}

function goTo(routeName) {
    router.visit(route(routeName));
    close();
}

function selectResult(result) {
    router.visit(route(result.route));
    close();
}

function selectHighlighted() {
    if (filteredResults.value.length > 0) {
        selectResult(filteredResults.value[highlightedIndex.value]);
    }
}

function highlightNext() {
    highlightedIndex.value = (highlightedIndex.value + 1) % filteredResults.value.length;
}

function highlightPrev() {
    highlightedIndex.value = (highlightedIndex.value - 1 + filteredResults.value.length) % filteredResults.value.length;
}

// Keyboard shortcut
function handleKeydown(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        if (props.isOpen) {
            close();
        } else {
            emit('open');
        }
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});
</script>
