<script setup>
import { ref, computed, watch} from 'vue';
const props = defineProps({
    items: Array,
    columns: Array,
    perPage: {
        type: Number,
        default: 5,
    },
});

const currentPage = ref(1);

const totalPages = computed(() =>
    Math.ceil(props.items.lenght / props.perPage)
);

const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * props.perPage;
    return props.items.slice(start, start + props.perPage);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

watch(() => props.items, () => {
    currentPage.value = 1;
});
</script>

<template>
    <div>
        <table class="w-full table-auto border border-collapse">
            <thead class="bg-gray-200">
                <tr>
                <th
                    v-for="column in columns"
                    :key="column.key"
                    class="border px-4 py-2"
                >
                    {{ column.label }}
                </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in paginatedItems" :key="item.id">
                <td
                    v-for="column in columns"
                    :key="column.key"
                    class="border px-4 py-2"
                >
                <slot
                    :name="`cell-${column.key}`"
                    :item="item"
                    :value="item[column.key]"
                >
                    {{ typeof item[column.key] === 'object' ? item[column.key]?.name || '-' : item[column.key] }} 

                </slot>
                    <!-- {{ item[column.key] }} -->
                </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
        <button
            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
            :disabled="currentPage === 1"
            @click="prevPage"
        >
            Anterior
        </button>

        <span>Página {{ currentPage }} de {{ totalPages }}</span>

        <button
            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
            :disabled="currentPage === totalPages"
            @click="nextPage"
        >
            Siguiente
        </button>
        </div>
    </div>
</template>