<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    leads: Array
})

const leads = ref(props.leads)


// Modal
const showModal = ref(false)
const editingLead = ref({
  id: null,
  nombre: '',
  correo: '',
  telefono: '',
  empresa: '',
  estatus: '',
})

function openEditModal(lead) {
  editingLead.value = { ...lead } // copia datos
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

function updateLead() {
  router.put(route('leads.update', editingLead.value.id), editingLead.value, {
    onSuccess: () => {
      showModal.value = false
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}

function deleteLead(id) {
  if (confirm('¿Estás seguro de eliminar este lead?')) {
    router.delete(route('leads.destroy', id))
  }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Leads
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="mb-4 text-lg font-semibold">Leads registrados</h3>
                        <button
                            @click="() => router.visit(route('leads.create'))"
                            class="inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 cursor-pointer"
                        >
                            Registrar Lead
                        </button>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Nombre</th>
                                    <th class="px-4 py-2 text-left">Correo</th>
                                    <th class="px-4 py-2 text-left">Teléfono</th>
                                    <th class="px-4 py-2 text-left">Empresa</th>
                                    <th class="px-4 py-2 text-left">Estatus</th>
                                    <th class="px-4 py-2 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in leads" :key="lead.id" class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2">{{ lead.nombre }}</td>
                                    <td class="px-4 py-2">{{ lead.correo }}</td>
                                    <td class="px-4 py-2">{{ lead.telefono }}</td>
                                    <td class="px-4 py-2">{{ lead.empresa }}</td>
                                    <td class="px-4 py-2">{{ lead.estatus }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                    <button
                                        @click="() => openEditModal(lead)"
                                        class="rounded bg-yellow-400 px-3 py-1 text-sm font-medium text-white hover:bg-yellow-500"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="() => deleteLead(lead.id)"
                                        class="rounded bg-red-600 px-3 py-1 text-sm font-medium text-white hover:bg-red-700"
                                    >
                                        Eliminar
                                    </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg dark:bg-gray-900">
    <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-gray-100">Editar Lead</h2>

    <form @submit.prevent="updateLead">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
        <input v-model="editingLead.nombre" type="text" class="w-full p-2 mt-1 border rounded-md dark:bg-gray-800 dark:text-white" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo</label>
        <input v-model="editingLead.correo" type="email" class="w-full p-2 mt-1 border rounded-md dark:bg-gray-800 dark:text-white" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
        <input v-model="editingLead.telefono" type="text" class="w-full p-2 mt-1 border rounded-md dark:bg-gray-800 dark:text-white" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Empresa</label>
        <input v-model="editingLead.empresa" type="text" class="w-full p-2 mt-1 border rounded-md dark:bg-gray-800 dark:text-white" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estatus</label>
        <input v-model="editingLead.estatus" type="text" class="w-full p-2 mt-1 border rounded-md dark:bg-gray-800 dark:text-white" />
      </div>

      <div class="flex justify-end space-x-2">
        <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
          Cancelar
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Guardar
        </button>
      </div>
    </form>
  </div>
</div>
    </AuthenticatedLayout>
</template>
