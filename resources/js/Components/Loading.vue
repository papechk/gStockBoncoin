<template>
    <div v-if="show" class="flex items-center justify-center" :class="containerClass">
        <div class="relative">
            <!-- Spinner -->
            <div
                :class="[
                    'rounded-full border-4 animate-spin',
                    sizeClasses,
                    colorClasses
                ]"
            ></div>

            <!-- Center dot -->
            <div
                v-if="size === 'lg'"
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"
            >
                <div class="w-3 h-3 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full animate-pulse"></div>
            </div>
        </div>

        <p v-if="message" class="ml-3 text-sm font-medium text-gray-700">{{ message }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: true
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    variant: {
        type: String,
        default: 'primary', // primary, secondary, white
        validator: (value) => ['primary', 'secondary', 'white'].includes(value)
    },
    message: {
        type: String,
        default: ''
    },
    fullScreen: {
        type: Boolean,
        default: false
    }
});

const sizeClasses = computed(() => {
    const classes = {
        sm: 'w-6 h-6 border-2',
        md: 'w-10 h-10 border-3',
        lg: 'w-16 h-16 border-4'
    };
    return classes[props.size];
});

const colorClasses = computed(() => {
    const classes = {
        primary: 'border-indigo-600 border-t-transparent',
        secondary: 'border-gray-300 border-t-transparent',
        white: 'border-white border-t-transparent'
    };
    return classes[props.variant];
});

const containerClass = computed(() => {
    if (props.fullScreen) {
        return 'fixed inset-0 bg-white/80 backdrop-blur-sm z-50';
    }
    return 'py-8';
});
</script>
