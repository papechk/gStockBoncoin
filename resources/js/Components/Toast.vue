<template>
    <div
        class="fixed bottom-4 right-4 z-50 transition-all duration-300 transform"
        :class="{
            'translate-y-0 opacity-100': show,
            'translate-y-2 opacity-0 pointer-events-none': !show
        }"
    >
        <div
            :class="[
                'flex items-center gap-3 px-6 py-4 rounded-xl shadow-2xl backdrop-blur-sm',
                'border-l-4 max-w-md',
                typeClasses
            ]"
        >
            <div class="flex-shrink-0">
                <component :is="iconComponent" class="w-6 h-6" />
            </div>
            <div class="flex-1">
                <p v-if="title" class="font-semibold text-sm mb-1">{{ title }}</p>
                <p class="text-sm">{{ message }}</p>
            </div>
            <button
                @click="close"
                class="flex-shrink-0 p-1 rounded-lg hover:bg-black/10 transition duration-200"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, watch, onMounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'info', // success, error, warning, info
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        required: true
    },
    duration: {
        type: Number,
        default: 5000 // Auto-close after 5 seconds
    }
});

const emit = defineEmits(['close']);

const typeClasses = computed(() => {
    const classes = {
        success: 'bg-green-50 border-green-500 text-green-900',
        error: 'bg-red-50 border-red-500 text-red-900',
        warning: 'bg-yellow-50 border-yellow-500 text-yellow-900',
        info: 'bg-blue-50 border-blue-500 text-blue-900'
    };
    return classes[props.type];
});

const iconComponent = computed(() => {
    const icons = {
        success: {
            template: `<svg fill="currentColor" class="text-green-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>`
        },
        error: {
            template: `<svg fill="currentColor" class="text-red-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>`
        },
        warning: {
            template: `<svg fill="currentColor" class="text-yellow-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>`
        },
        info: {
            template: `<svg fill="currentColor" class="text-blue-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>`
        }
    };
    return icons[props.type];
});

function close() {
    emit('close');
}

let timer = null;

watch(() => props.show, (value) => {
    if (value && props.duration > 0) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            close();
        }, props.duration);
    }
});

onMounted(() => {
    if (props.show && props.duration > 0) {
        timer = setTimeout(() => {
            close();
        }, props.duration);
    }
});
</script>
