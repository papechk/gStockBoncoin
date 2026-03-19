<template>
    <div class="overflow-hidden rounded-xl shadow-lg border border-gray-200 bg-white">
        <!-- Table Header -->
        <div v-if="$slots.header" class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
            <slot name="header"></slot>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Head -->
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="(column, index) in columns"
                            :key="index"
                            scope="col"
                            :class="[
                                'px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider',
                                column.headerClass || ''
                            ]"
                        >
                            {{ column.label }}
                        </th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <slot name="body">
                        <tr v-if="data.length === 0">
                            <td :colspan="columns.length" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-lg font-medium">{{ emptyMessage }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(row, rowIndex) in data"
                            :key="rowIndex"
                            class="hover:bg-gray-50 transition duration-150"
                        >
                            <td
                                v-for="(column, colIndex) in columns"
                                :key="colIndex"
                                :class="[
                                    'px-6 py-4 whitespace-nowrap text-sm',
                                    column.cellClass || 'text-gray-900'
                                ]"
                            >
                                <slot :name="`cell-${column.key}`" :row="row" :value="row[column.key]">
                                    {{ row[column.key] }}
                                </slot>
                            </td>
                        </tr>
                    </slot>
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
defineProps({
    columns: {
        type: Array,
        required: true
        // Format: [{ key: 'name', label: 'Nom', headerClass: '', cellClass: '' }]
    },
    data: {
        type: Array,
        default: () => []
    },
    emptyMessage: {
        type: String,
        default: 'Aucune donnée disponible'
    }
});
</script>
