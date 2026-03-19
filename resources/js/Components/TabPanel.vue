<template>
    <div v-show="isActive" class="animate-fade-in">
        <slot></slot>
    </div>
</template>

<script setup>
import { computed, inject } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    }
});

const activeTab = inject('activeTab');

const isActive = computed(() => {
    return activeTab.value === props.id;
});
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
