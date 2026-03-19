<template>
    <!-- Sidebar pour desktop -->
    <div class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 lg:z-40">
        <!-- Sidebar content -->
        <div class="flex flex-col h-full bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 overflow-y-auto shadow-2xl relative">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Logo -->
            <div class="flex items-center flex-shrink-0 px-6 py-6 border-b border-white/10 relative z-10">
                <div class="flex items-center w-full">
                    <div class="bg-gradient-to-br from-white to-indigo-100 rounded-2xl p-2.5 mr-3 shadow-lg">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white tracking-tight">gStock</h1>
                        <p class="text-xs text-indigo-300 font-medium">Gestion intelligente</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="px-4 py-3 border-b border-white/10 relative z-10">
                <div class="grid grid-cols-2 gap-2.5">
                    <div class="bg-white/5 backdrop-blur-sm rounded-lg p-3 border border-white/10 hover:bg-white/10 transition-all duration-200 cursor-pointer hover:scale-105">
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between mb-1">
                                <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg p-1.5 shadow-lg">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-white mb-0.5">{{ stats?.ventesJour || 0 }}</p>
                            <p class="text-xs text-indigo-300 font-medium">Ventes/Jour</p>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm rounded-lg p-3 border border-white/10 hover:bg-white/10 transition-all duration-200 cursor-pointer hover:scale-105">
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between mb-1">
                                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg p-1.5 shadow-lg" :class="stats?.alertesStock > 0 ? 'animate-pulse' : ''">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-white mb-0.5">{{ stats?.alertesStock || 0 }}</p>
                            <p class="text-xs text-indigo-300 font-medium">Alertes Stock</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 relative z-10 overflow-y-auto">
                <!-- Section: Vue d'ensemble -->
                <div class="mb-3">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Vue d'ensemble</p>

                    <!-- Dashboard -->
                    <Link :href="route('dashboard')"
                          :class="[
                              route().current('dashboard')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-0.5',
                              'group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200'
                          ]">
                        <div :class="route().current('dashboard') ? 'bg-white/20' : 'bg-indigo-800/40'" class="mr-2.5 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="flex-1">Dashboard</span>
                        <svg v-if="route().current('dashboard')" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </Link>
                </div>

                <!-- Section: Ventes & Commerce -->
                <div class="mb-3">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Ventes & Commerce</p>

                    <!-- Ventes -->
                    <Link :href="route('ventes.index')"
                          :class="[
                              route().current('ventes.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200'
                          ]">
                        <div :class="route().current('ventes.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="flex-1">Ventes</span>
                        <span v-if="route().current('ventes.create')" class="px-2 py-0.5 text-xs font-semibold bg-green-500 rounded-full">Nouveau</span>
                    </Link>

                    <!-- Clients -->
                    <Link :href="route('clients.index')"
                          :class="[
                              route().current('clients.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 mt-1'
                          ]">
                        <div :class="route().current('clients.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="flex-1">Clients</span>
                    </Link>
                </div>

                <!-- Section: Inventaire (Admin + Gestionnaire) -->
                <div v-if="canManageProducts" class="mb-3">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Inventaire</p>

                    <!-- Produits -->
                    <Link :href="route('produits.index')"
                          :class="[
                              route().current('produits.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200'
                          ]">
                        <div :class="route().current('produits.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="flex-1">Produits</span>
                        <span v-if="stats?.alertesStock > 0" class="px-2 py-0.5 text-xs font-semibold bg-red-500 rounded-full animate-pulse">{{ stats.alertesStock }}</span>
                    </Link>

                    <!-- Catégories -->
                    <Link :href="route('categories.index')"
                          :class="[
                              route().current('categories.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 mt-1'
                          ]">
                        <div :class="route().current('categories.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="flex-1">Catégories</span>
                    </Link>

                    <!-- Mouvements Stock -->
                    <Link :href="route('mvtstock.index')"
                          :class="[
                              route().current('mvtstock.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 mt-1'
                          ]">
                        <div :class="route().current('mvtstock.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                        <span class="flex-1">Stock</span>
                    </Link>
                </div>

                <!-- Section: Administration (Admin uniquement) -->
                <div v-if="isAdmin" class="mb-3">
                    <p class="px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Administration</p>

                    <!-- Utilisateurs -->
                    <Link :href="route('users.index')"
                          :class="[
                              route().current('users.*')
                                  ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/50'
                                  : 'text-indigo-100 hover:bg-white/10 hover:translate-x-1',
                              'group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200'
                          ]">
                        <div :class="route().current('users.*') ? 'bg-white/20' : 'bg-indigo-800/50'" class="mr-3 p-1.5 rounded-lg group-hover:scale-110 transition-transform duration-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="flex-1">Utilisateurs</span>
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </Link>
                </div>
            </nav>

            <!-- User info -->
            <div class="flex-shrink-0 px-4 py-4 border-t border-white/10 relative z-10">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                    <div class="flex items-center mb-3">
                        <div class="relative flex-shrink-0">
                            <div class="bg-gradient-to-br from-pink-500 via-purple-500 to-indigo-500 rounded-xl w-12 h-12 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-slate-900 rounded-full"></div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ user.name }}</p>
                            <p class="text-xs text-indigo-300 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                {{ user.role?.nom || 'Utilisateur' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 bg-white/10 hover:bg-white/20 text-white rounded-lg px-3 py-2 text-xs font-medium transition duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Profil
                        </button>
                        <Link :href="route('logout')" method="post" as="button"
                              class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg px-3 py-2 text-xs font-medium transition duration-200 flex items-center justify-center shadow-lg hover:shadow-red-500/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div v-if="showMobileSidebar" @click="$emit('close')" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden"></div>

    <!-- Mobile sidebar -->
    <div v-if="showMobileSidebar" class="fixed inset-y-0 left-0 flex flex-col w-64 bg-gradient-to-b from-indigo-900 via-indigo-800 to-purple-900 z-40 lg:hidden transform transition-transform duration-300">
        <!-- Mobile content (same as desktop) -->
        <div class="flex flex-col flex-grow overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-6 border-b border-indigo-700">
                <div class="flex items-center">
                    <div class="bg-white rounded-xl p-2 mr-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">gStock</h1>
                        <p class="text-xs text-indigo-300">Gestion de stock</p>
                    </div>
                </div>
                <button @click="$emit('close')" class="text-indigo-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Same navigation as desktop -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <Link :href="route('dashboard')" @click="$emit('close')" :class="route().current('dashboard') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10'" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </Link>
                <Link :href="route('ventes.index')" @click="$emit('close')" :class="route().current('ventes.*') ? 'bg-white/20 text-white' : 'text-indigo-100 hover:bg-white/10'" class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Ventes
                </Link>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    showMobileSidebar: Boolean,
    stats: {
        type: Object,
        default: () => ({
            ventesJour: 0,
            alertesStock: 0
        })
    }
});

defineEmits(['close']);

const isAdmin = computed(() => props.user?.role?.nom === 'Admin');
const canManageProducts = computed(() => ['Admin', 'gestionnaire_stock'].includes(props.user?.role?.nom));
</script>
