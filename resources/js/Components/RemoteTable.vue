<!-- resources/js/Components/RemoteTable.vue -->
<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  apiUrl: String, // URL del endpoint, ej: '/api/leads'
  columns: Array, // [{ label: 'Nombre', key: 'name' }]
  perPage: {
    type: Number,
    default: 10,
  },
  searchable: {
    type: Boolean,
    default: false,
  },
})

const items = ref([])
const pagination = ref({})
const loading = ref(false)
const search = ref('')
const sortBy = ref('')
const sortDir = ref('asc')
const currentPage = ref(1)

const fetchData = async () => {
  loading.value = true
  try {
    const response = await axios.get(props.apiUrl, {
      params: {
        page: currentPage.value,
        per_page: props.perPage,
        search: search.value,
        sort_by: sortBy.value,
        sort_dir: sortDir.value,
      },
    })

    items.value = response.data.data
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
    }
  } catch (error) {
    console.error('Error al cargar datos:', error)
  } finally {
    loading.value = false
  }
}

const getNestedValue = (obj, path) => {
  return path.split('.').reduce((acc, part) => acc && acc[part], obj)
}

const changePage = (page) => {
  currentPage.value = page
  fetchData()
}

const toggleSort = (column) => {
  if (sortBy.value === column) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = column
    sortDir.value = 'asc'
  }
  fetchData()
}

watch([search], () => {
  currentPage.value = 1
  fetchData()
})

onMounted(() => fetchData())
</script>

<template>
  <div>
    <div v-if="searchable" class="mb-3">
      <input
        v-model="search"
        placeholder="Buscar..."
        class="w-full p-2 border rounded"
      />
    </div>

    <table class="w-full table-auto border border-collapse">
      <thead class="bg-gray-200">
        <tr>
          <th
            v-for="col in columns"
            :key="col.key"
            class="border px-4 py-2 cursor-pointer"
            @click="toggleSort(col.key)"
          >
            {{ col.label }}
            <span v-if="sortBy === col.key">
              {{ sortDir === 'asc' ? '▲' : '▼' }}
            </span>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td :colspan="columns.length" class="text-center py-4">Cargando...</td>
        </tr>
        <tr v-for="item in items" :key="item.id">
          <td v-for="col in columns" :key="col.key" class="border px-4 py-2">
            {{ getNestedValue(item, col.key) }}
          </td>
        </tr>
        <tr v-if="!loading && items.length === 0">
          <td :colspan="columns.length" class="text-center py-4">Sin resultados</td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4 flex justify-between items-center">
      <button
        class="bg-gray-300 px-3 py-1 rounded"
        :disabled="pagination.current_page === 1"
        @click="changePage(pagination.current_page - 1)"
      >
        Anterior
      </button>
      <span>
        Página {{ pagination.current_page }} de {{ pagination.last_page }}
      </span>
      <button
        class="bg-gray-300 px-3 py-1 rounded"
        :disabled="pagination.current_page === pagination.last_page"
        @click="changePage(pagination.current_page + 1)"
      >
        Siguiente
      </button>
    </div>
  </div>
</template>
