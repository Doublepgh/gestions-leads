<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import axios from 'axios'


const asignaciones = ref([])

const formatFecha = fecha => new Date(fecha).toLocaleString()

const fetchAsignaciones = async () => {
  try {
    const response = await axios.get('/api/asignaciones')
    asignaciones.value = response.data
  } catch (error) {
    console.error('Error al obtener asignaciones:', error)
  }
}


onMounted(fetchAsignaciones)
</script>

<template>
  <Head title="Asignaciones" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Asignaciones
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead>
              <tr class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <th class="p-2 text-left">ID</th>
                <th class="p-2 text-left">Lead</th>
                <th class="p-2 text-left">Operador</th>
                <th class="p-2 text-left">Asignado En</th>
                <th class="p-2 text-left">Cerrado En</th>
                <th class="p-2 text-left">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="asignacion in asignaciones"
                :key="asignacion.id"
                class="border-t hover:bg-gray-100 dark:hover:bg-gray-700"
              >
                <td class="p-2">{{ asignacion.id }}</td>
                <td class="p-2">{{ asignacion.lead?.nombre ?? '—' }}</td>
                <td class="p-2">{{ asignacion.operador?.name ?? '—' }}</td>
                <td class="p-2">{{ formatFecha(asignacion.asignado_en) }}</td>
                <td class="p-2">{{ asignacion.cerrado_en ? formatFecha(asignacion.cerrado_en) : '—' }}</td>
                <td class="p-2">
                  <button
                    v-if="!asignacion.cerrado_en"
                    @click="cerrar(asignacion.id)"
                    class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600"
                  >
                    Cerrar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


