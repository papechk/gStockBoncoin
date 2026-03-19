<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <!-- Backdrop -->
                <div
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm"
                    @click="cancel"
                ></div>

                <!-- Dialog -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="show"
                            class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                        >
                            <!-- Icon -->
                            <div class="flex items-center justify-center pt-6 pb-4">
                                <div :class="['p-4 rounded-full', iconBgClass]">
                                    <component :is="iconComponent" class="w-8 h-8 text-white" />
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="px-6 pb-6 text-center">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ title }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ message }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <button
                                    @click="cancel"
                                    class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200"
                                    :disabled="loading"
                                >
                                    {{ cancelText }}
                                </button>
                                <button
                                    @click="confirm"
                                    :disabled="loading"
                                    :class="[
                                        'flex-1 px-4 py-2.5 text-sm font-medium text-white rounded-lg shadow-lg transition duration-200',
                                        'disabled:opacity-50 disabled:cursor-not-allowed',
                                        confirmButtonClass
                                    ]"
                                >
                                    <span v-if="loading" class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Traitement...</span>
                                    </span>
                                    <span v-else>{{ confirmText }}</span>
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Confirmer l\'action'
    },
    message: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'danger', // danger, warning, info, success
        validator: (value) => ['danger', 'warning', 'info', 'success'].includes(value)
    },
    confirmText: {
        type: String,
        default: 'Confirmer'
    },
    cancelText: {
        type: String,
        default: 'Annuler'
    },
    loading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['confirm', 'cancel', 'close']);

const iconBgClass = computed(() => {
    const classes = {
        danger: 'bg-gradient-to-br from-red-500 to-pink-600',
        warning: 'bg-gradient-to-br from-yellow-500 to-orange-600',
        info: 'bg-gradient-to-br from-blue-500 to-indigo-600',
        success: 'bg-gradient-to-br from-green-500 to-emerald-600'
    };
    return classes[props.type];
});

const confirmButtonClass = computed(() => {
    const classes = {
        danger: 'bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 hover:shadow-red-500/50',
        warning: 'bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 hover:shadow-yellow-500/50',
        info: 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 hover:shadow-blue-500/50',
        success: 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-green-500/50'
    };
    return classes[props.type];
});

const iconComponent = computed(() => {
    const icons = {
        danger: {
            template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`
        },
        warning: {
            template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`
        },
        info: {
            template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
        },
        success: {
            template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
        }
    };
    return icons[props.type];
});

function confirm() {
    emit('confirm');
}

function cancel() {
    emit('cancel');
    emit('close');
}

function handleEscape(e) {
    if (e.key === 'Escape' && props.show && !props.loading) {
        cancel();
    }
}

watch(() => props.show, (value) => {
    if (value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onMounted(() => {
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
});
</script>
