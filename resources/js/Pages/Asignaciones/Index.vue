<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import RemoteTable from '@/Components/RemoteTable.vue'
import axios from 'axios'

const columns = [
  { key: 'id', label: 'ID' },
  { key: 'lead', label: 'Lead' },
  { key: 'operador', label: 'Operador' },
  { key: 'asignado_en', label: 'Asignado En' },
  { key: 'cerrado_en', label: 'Cerrado En' },
  { key: 'actions', label: 'Acciones' },
]

const cerrarAsignacion = async (id) => {
  try {php
    await axios.post(`/api/asignaciones/${id}/cerrar`)
    // RemoteTable se recargará automáticamente si emites un evento, o puedes usar una ref si lo adaptas
  } catch (error) {
    console.error('Error al cerrar asignación:', error)
  }
}

</script>

<template>
  <Head title="Asignaciones" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Asignaciones
      </h2>
    </template>

    <h2 class="text-xl font-bold mb-4">TABLA DE ASIGNACIONES</h2>
    <div>
        <a
        :href="route('asignaciones.pdf')"
        target="_blank"
        class="inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 cursor-pointer"
        >
            Generar Reporte
        </a>
        <Link
            href="/graficas"
            class="inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700"
        >
            Ver Gráficos
        </Link>
      </div>

    <RemoteTable
      api-url="/api/asignaciones"
      :columns="columns"
      :per-page="5"
      :searchable="true"
    >
      <template #cell-actions="{ item }">
        <button
          class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
          @click="cerrarAsignacion(item.id)"
        >
          Cerrar
        </button>
      </template>
    </RemoteTable>
  </AuthenticatedLayout>
</template>