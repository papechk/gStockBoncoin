<template>
    <div
        :class="[
            'relative overflow-hidden rounded-xl p-6 transition-all duration-300',
            'hover:shadow-2xl hover:-translate-y-1 cursor-pointer',
            'border border-gray-200',
            bgClass
        ]"
    >
        <!-- Decorative blur -->
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full opacity-30 blur-3xl" :class="blurClass"></div>

        <div class="relative z-10">
            <!-- Icon & Title -->
            <div class="flex items-start justify-between mb-4">
                <div :class="['p-3 rounded-xl shadow-lg', iconBgClass]">
                    <component v-if="icon" :is="icon" class="w-6 h-6 text-white" />
                    <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div v-if="trend" :class="['flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold', trendClass]">
                    <svg v-if="trend > 0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                    <span>{{ Math.abs(trend) }}%</span>
                </div>
            </div>

            <!-- Value -->
            <div class="mb-2">
                <div class="text-3xl font-bold text-gray-900">
                    {{ formattedValue }}
                </div>
            </div>

            <!-- Title & Subtitle -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700">{{ title }}</h3>
                <p v-if="subtitle" class="text-xs text-gray-500 mt-1">{{ subtitle }}</p>
            </div>

            <!-- Footer action -->
            <div v-if="$slots.footer" class="mt-4 pt-4 border-t border-gray-200">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: {
        type: String,
        default: ''
    },
    value: {
        type: [Number, String],
        required: true
    },
    icon: {
        type: [String, Object],
        default: null
    },
    trend: {
        type: Number,
        default: null // positive or negative percentage
    },
    variant: {
        type: String,
        default: 'indigo', // indigo, purple, green, red, yellow, blue
        validator: (value) => ['indigo', 'purple', 'green', 'red', 'yellow', 'blue'].includes(value)
    },
    format: {
        type: String,
        default: 'number', // number, currency, percentage
        validator: (value) => ['number', 'currency', 'percentage'].includes(value)
    }
});

const bgClass = computed(() => {
    const classes = {
        indigo: 'bg-gradient-to-br from-indigo-50 to-white',
        purple: 'bg-gradient-to-br from-purple-50 to-white',
        green: 'bg-gradient-to-br from-green-50 to-white',
        red: 'bg-gradient-to-br from-red-50 to-white',
        yellow: 'bg-gradient-to-br from-yellow-50 to-white',
        blue: 'bg-gradient-to-br from-blue-50 to-white'
    };
    return classes[props.variant];
});

const blurClass = computed(() => {
    const classes = {
        indigo: 'bg-indigo-400',
        purple: 'bg-purple-400',
        green: 'bg-green-400',
        red: 'bg-red-400',
        yellow: 'bg-yellow-400',
        blue: 'bg-blue-400'
    };
    return classes[props.variant];
});

const iconBgClass = computed(() => {
    const classes = {
        indigo: 'bg-gradient-to-br from-indigo-500 to-indigo-600',
        purple: 'bg-gradient-to-br from-purple-500 to-purple-600',
        green: 'bg-gradient-to-br from-green-500 to-green-600',
        red: 'bg-gradient-to-br from-red-500 to-red-600',
        yellow: 'bg-gradient-to-br from-yellow-500 to-yellow-600',
        blue: 'bg-gradient-to-br from-blue-500 to-blue-600'
    };
    return classes[props.variant];
});

const trendClass = computed(() => {
    if (props.trend > 0) {
        return 'bg-green-100 text-green-800';
    } else {
        return 'bg-red-100 text-red-800';
    }
});

const formattedValue = computed(() => {
    const value = Number(props.value) || 0;

    switch (props.format) {
        case 'currency':
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'MAD',
                minimumFractionDigits: 0
            }).format(value);
        case 'percentage':
            return `${value}%`;
        default:
            return new Intl.NumberFormat('fr-FR').format(value);
    }
});
</script>
