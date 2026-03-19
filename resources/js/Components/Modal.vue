<template>
    <div class="fixed inset-0 z-50 overflow-y-auto" v-if="show">
        <!-- Backdrop -->
        <div
            class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300"
            :class="show ? 'opacity-100' : 'opacity-0'"
            @click="close"
        ></div>

        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div
                class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all duration-300"
                :class="show ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg p-2 mr-3">
                            <slot name="icon">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </slot>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ title }}</h3>
                            <p v-if="subtitle" class="text-sm text-gray-500">{{ subtitle }}</p>
                        </div>
                    </div>
                    <button
                        @click="close"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    <slot></slot>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl">
                    <slot name="footer">
                        <button
                            @click="close"
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-indigo-500/50 transition duration-200"
                        >
                            Enregistrer
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['close']);

function close() {
    emit('close');
}

function handleEscape(e) {
    if (e.key === 'Escape' && props.show) {
        close();
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
