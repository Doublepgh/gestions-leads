<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({ operadores: Array });

const operadoresLocal = ref([...props.operadores]);
const operadorIdToDelete = ref(null);
const isConfirmModalOpen = ref(false);
const isEditModalOpen = ref(false);
const successMessage = ref('');
const API_URL = '/api/operadores';

// Datos para editar
const operadorEdit = ref({
  id: null,
  name: '',
  email: '',
  username: '',
  activo: true,
});

function cambiarModo(operador) {
  router.put(route('usuarios.cambiarModo', operador.id), {
    modo_asignacion: operador.modo_asignacion,
  });
}

// =======================
// Eliminar operador
// =======================
const confirmDelete = (operador) => {
  operadorIdToDelete.value = operador.id;
  isConfirmModalOpen.value = true;
};

const closeConfirmModal = () => {
  operadorIdToDelete.value = null;
  isConfirmModalOpen.value = false;
};

const deleteOperador = async () => {
  try {
    await axios.delete(`${API_URL}/${operadorIdToDelete.value}`);
    operadoresLocal.value = operadoresLocal.value.filter(o => o.id !== operadorIdToDelete.value);
    successMessage.value = 'Operador eliminado correctamente.';
  } catch (error) {
    console.error('Error al eliminar operador:', error);
    alert('Error al eliminar el operador.');
  } finally {
    closeConfirmModal();
  }
};

// =======================
// Editar operador
// =======================
const openEditModal = (operador) => {
  operadorEdit.value = { ...operador };
  isEditModalOpen.value = true;
};

const closeEditModal = () => {
  operadorEdit.value = {
    id: null,
    name: '',
    email: '',
    username: '',
    activo: true,
  };
  isEditModalOpen.value = false;
};

const updateOperador = async () => {
  try {
    await axios.put(`${API_URL}/${operadorEdit.value.id}`, {
  name: operadorEdit.value.name,
  username: operadorEdit.value.username,
  email: operadorEdit.value.email,
  activo: operadorEdit.value.activo,
});

    // Actualiza la lista local
    const index = operadoresLocal.value.findIndex(o => o.id === operadorEdit.value.id);
    if (index !== -1) {
      operadoresLocal.value[index] = { ...operadorEdit.value };
    }

    successMessage.value = 'Operador actualizado correctamente.';
    closeEditModal();
  } catch (error) {
    console.error('Error al actualizar operador:', error);
    alert('Verifica los datos ingresados.');
  }
};
</script>

<template>
  <Head title="Operadores" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Lista de Operadores
      </h2>
    </template>
    <button
  @click="$inertia.visit(route('operadores.create'))"
  class="inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 cursor-pointer"
>
  Registrar Operador
</button>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead>
              <tr>
                <th class="px-4 py-2 text-left">Usuario</th>
                <th class="px-4 py-2 text-left">Nombre</th>
                <th class="px-4 py-2 text-left">Correo</th>
                <th class="px-4 py-2 text-left">Estatus</th>
                <th class="px-4 py-2 text-left">Acciones</th>
                <th class="px-4 py-2 text-left">Modo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in operadoresLocal" :key="user.id" class="border-t dark:border-gray-700">
                <td class="px-4 py-2">{{ user.username }}</td>
                <td class="px-4 py-2">{{ user.name }}</td>
                <td class="px-4 py-2">{{ user.email }}</td>
                
                <td class="px-4 py-2">
                  <span :class="user.activo ? 'text-green-500' : 'text-red-500'">
                    {{ user.activo ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900 text-center">
                  <div class="flex items-center justify-center space-x-2">
                    <button
                      @click="openEditModal(user)"
                      class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition duration-200"
                      title="Editar"
                    >
                      Editar
                      <Pencil class="inline-block w-4 h-4" />
                    </button>
                    <button
                      @click="confirmDelete(user)"
                      class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition duration-200"
                      title="Eliminar"
                    >
                      Eliminar
                      <Trash2 class="inline-block w-4 h-4" />
                    </button>
                  </div>
                </td>
                <td>
                  <select v-model="user.modo_asignacion" @change="cambiarModo(user)">
                    <option value="manual">Manual</option>
                    <option value="automatico">Automático</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="successMessage" class="text-green-600 text-center mt-4">
          {{ successMessage }}
        </div>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <div
      v-if="isConfirmModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded-lg shadow-md w-1/3">
        <h2 class="text-xl font-bold mb-4">¿Eliminar operador?</h2>
        <p class="mb-6 text-gray-600">Esta acción no se puede deshacer.</p>
        <div class="flex justify-end gap-4">
          <button @click="closeConfirmModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Cancelar
          </button>
          <button @click="deleteOperador" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de Edición -->
    <div
      v-if="isEditModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-xl font-semibold mb-6">Editar Operador</h2>

        <form @submit.prevent="updateOperador" class="space-y-4">
          <div>
            <label class="block font-medium">Nombre</label>
            <input v-model="operadorEdit.name" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <div>
            <label class="block font-medium">Correo</label>
            <input v-model="operadorEdit.email" type="email" class="w-full border rounded px-3 py-2" required />
          </div>
          <div>
            <label class="block font-medium">Usuario</label>
            <input v-model="operadorEdit.username" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <div>
            <label class="block font-medium">Estatus</label>
            <select v-model="operadorEdit.activo" class="w-full border rounded px-3 py-2">
              <option :value="true">Activo</option>
              <option :value="false">Inactivo</option>
            </select>
          </div>

          <div class="flex justify-end gap-4">
            <button type="button" @click="closeEditModal" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
              Cancelar
            </button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Guardar Cambios
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
