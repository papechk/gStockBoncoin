<template>
    <div
        :class="[
            'rounded-xl shadow-lg overflow-hidden transition-all duration-300',
            'hover:shadow-2xl hover:-translate-y-1',
            'bg-white border border-gray-200'
        ]"
    >
        <!-- Header -->
        <div
            v-if="$slots.header || title"
            :class="[
                'px-6 py-4 border-b border-gray-200',
                headerClass || 'bg-gradient-to-r from-indigo-50 to-purple-50'
            ]"
        >
            <slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">{{ title }}</h3>
                    <slot name="headerActions"></slot>
                </div>
            </slot>
        </div>

        <!-- Content -->
        <div :class="['p-6', contentClass]">
            <slot></slot>
        </div>

        <!-- Footer -->
        <div
            v-if="$slots.footer"
            :class="[
                'px-6 py-4 border-t border-gray-200',
                footerClass || 'bg-gray-50'
            ]"
        >
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: ''
    },
    headerClass: {
        type: String,
        default: ''
    },
    contentClass: {
        type: String,
        default: ''
    },
    footerClass: {
        type: String,
        default: ''
    }
});
</script>
