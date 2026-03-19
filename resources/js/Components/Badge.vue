<template>
    <span
        :class="[
            'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
            variantClasses
        ]"
    >
        <span v-if="dot" class="w-2 h-2 rounded-full mr-2" :class="dotColor"></span>
        <slot></slot>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'default', // default, success, danger, warning, info, purple
        validator: (value) => ['default', 'success', 'danger', 'warning', 'info', 'purple'].includes(value)
    },
    dot: {
        type: Boolean,
        default: false
    }
});

const variantClasses = computed(() => {
    const classes = {
        default: 'bg-gray-100 text-gray-800',
        success: 'bg-green-100 text-green-800',
        danger: 'bg-red-100 text-red-800',
        warning: 'bg-yellow-100 text-yellow-800',
        info: 'bg-blue-100 text-blue-800',
        purple: 'bg-purple-100 text-purple-800'
    };
    return classes[props.variant];
});

const dotColor = computed(() => {
    const colors = {
        default: 'bg-gray-800',
        success: 'bg-green-800',
        danger: 'bg-red-800',
        warning: 'bg-yellow-800',
        info: 'bg-blue-800',
        purple: 'bg-purple-800'
    };
    return colors[props.variant];
});
</script>
