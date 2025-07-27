<template>
  <Head title="Leads" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Leads
      </h2>
    </template>

    <div class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow-md space-y-6">
      <h2 class="text-2xl font-semibold text-gray-700 text-center">Registro de Lead</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <!-- Nombre -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Nombre</label>
          <input
            v-model="form.nombre"
            type="text"
            required
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          />
        </div>

        <!-- Correo -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Correo</label>
          <input
            v-model="form.correo"
            type="email"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          />
        </div>

        <!-- Teléfono -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
          <input
            v-model="form.telefono"
            type="text"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          />
        </div>

        <!-- Empresa -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Empresa</label>
          <input
            v-model="form.empresa"
            type="text"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          />
        </div>

        <!-- Interés -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Interés</label>
          <textarea
            v-model="form.interes"
            rows="3"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          ></textarea>
        </div>

        <!-- Tipo de asignación -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Tipo de asignación</label>
          <select
            v-model="form.asignacion_tipo"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          >
            <option value="automatica">Automática</option>
            <option value="manual">Manual</option>
          </select>
        </div>

        <!-- Campo de búsqueda -->
<div v-if="form.asignacion_tipo === 'manual'" class="mb-2">
  <input
    v-model="search"
    @input="fetchOperadores"
    placeholder="Buscar operador por nombre..."
    class="w-full border border-gray-300 p-2 rounded-lg"
  />
</div>

<!-- Select de operadores -->
<div v-if="form.asignacion_tipo === 'manual'">
  <label class="block text-sm font-medium text-gray-600 mb-1">Asignar a operador</label>
  <select
    v-model="form.operador_id"
    class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
  >
    <option value="" disabled selected>Seleccione un operador</option>
    <option
      v-for="operador in operadores"
      :key="operador.id"
      :value="operador.id"
    >
      {{ operador.name }}
    </option>
  </select>
</div>

        <!-- Botón -->
        <div class="text-center">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-xl transition-all duration-200"
          >
            Guardar Lead
          </button>
        </div>
      </form>

      <!-- Mensaje de éxito -->
      <div v-if="successMessage" class="text-green-600 text-center font-medium">
        {{ successMessage }}
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { reactive, ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const form = reactive({
  nombre: '',
  correo: '',
  telefono: '',
  empresa: '',
  interes: '',
})

const operadores = ref([])
const search = ref('')
const errors = ref({})
const successMessage = ref('')

// Llama cuando se escribe en el input
const fetchOperadores = async () => {
  try {
    const response = await axios.get('/api/operadores', {
      params: {
        search: search.value,
        modo: 'manual',
      }
    })
    operadores.value = response.data
  } catch (err) {
    console.error('Error al buscar operadores:', err)
  }

  
}

// Al cargar por primera vez (si es manual), traer operadores
watch(() => form.asignacion_tipo, (tipo) => {
  if (tipo === 'manual') {
    fetchOperadores()
  }
})

const submit = async () => {
  try {
    const payload = {
      nombre: form.nombre,
      correo: form.correo,
      telefono: form.telefono,
      empresa: form.empresa,
      interes: form.interes,
    }

    if (form.asignacion_tipo === 'manual' && form.operador_id) {
      payload.operador_id = form.operador_id
    }

    await axios.post('/api/leads', payload)

    successMessage.value = 'Lead guardado correctamente'

    setTimeout(() => {
      router.visit('/dashboard')
    }, 2000)
  } catch (err) {
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors
    } else {
      console.error(err)
      alert('Ocurrió un error al guardar el lead.')
    }
  }
}
</script>
