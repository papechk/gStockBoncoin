<template>
    <div>
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="selectTab(tab.id)"
                    :class="[
                        'group inline-flex items-center gap-2 py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200',
                        activeTab === tab.id
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    ]"
                >
                    <component
                        v-if="tab.icon"
                        :is="tab.icon"
                        :class="[
                            'w-5 h-5 transition-colors',
                            activeTab === tab.id
                                ? 'text-indigo-600'
                                : 'text-gray-400 group-hover:text-gray-500'
                        ]"
                    />
                    <span>{{ tab.label }}</span>
                    <span
                        v-if="tab.badge"
                        :class="[
                            'ml-1 py-0.5 px-2 rounded-full text-xs font-semibold',
                            activeTab === tab.id
                                ? 'bg-indigo-100 text-indigo-600'
                                : 'bg-gray-100 text-gray-600'
                        ]"
                    >
                        {{ tab.badge }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import { ref, provide } from 'vue';

const props = defineProps({
    tabs: {
        type: Array,
        required: true
        // Format: [{ id: 'tab1', label: 'Tab 1', icon: IconComponent, badge: '5' }]
    },
    defaultTab: {
        type: String,
        default: null
    }
});

const emit = defineEmits(['change']);

const activeTab = ref(props.defaultTab || props.tabs[0]?.id);

function selectTab(tabId) {
    activeTab.value = tabId;
    emit('change', tabId);
}

// Provide active tab to child TabPanel components
provide('activeTab', activeTab);
</script>
