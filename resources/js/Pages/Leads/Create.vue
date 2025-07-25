<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


// URLs de la API
const API_URL = "leads";

defineProps({
  operadores: Array
})

const form = reactive({
  nombre: '',
  correo: '',
  telefono: '',
  empresa: '',
  interes: '',
  asignacion_tipo: 'automatica',
  operador_id: null
})

const errors = ref({})

const submit = async () => {
  try {
    const payload = {
      nombre: form.nombre,
      correo: form.correo,
      telefono: form.telefono,
      empresa: form.empresa,
      interes: form.interes,
      estatus: 'abierto', // o déjalo que lo asigne el backend por defecto
      creado_por: null, // se asignará desde el token en backend
    }

    // Asignación manual solo si corresponde
    if (form.asignacion_tipo === 'manual' && form.operador_id) {
      payload.operador_id = form.operador_id
    }

    await axios.post('/api/leads', payload).then(res => {
        alert(res.data.message) // muestra mensaje
        router.visit('/dashboard') // redirige al dashboard
    })

    successMessage.value = 'Lead guardado correctamente'
    // limpia el formulario si deseas
    setTimeout(() => {
      window.location.href = '/dashboard'
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

        <!-- Operador (solo si es manual) -->
        <div v-if="form.asignacion_tipo === 'manual'">
          <label class="block text-sm font-medium text-gray-600 mb-1">Asignar a operador</label>
          <select
            v-model="form.operador_id"
            class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300"
          >
            <option
              v-for="operador in operadores"
              :key="operador.id"
              :value="operador.id"
            >
              {{ operador.nombre }}
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
