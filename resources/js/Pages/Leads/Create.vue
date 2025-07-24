<template>
  <form @submit.prevent="submit">
    <div>
      <label>Nombre</label>
      <input v-model="form.nombre" type="text" required />
    </div>

    <div>
      <label>Correo</label>
      <input v-model="form.correo" type="email" />
    </div>

    <div>
      <label>Teléfono</label>
      <input v-model="form.telefono" type="text" />
    </div>

    <div>
      <label>Empresa</label>
      <input v-model="form.empresa" type="text" />
    </div>

    <div>
      <label>Interés</label>
      <textarea v-model="form.interes"></textarea>
    </div>

    <div>
      <label>Tipo de asignación</label>
      <select v-model="form.asignacion_tipo">
        <option value="automatica">Automática</option>
        <option value="manual">Manual</option>
      </select>
    </div>

    <div v-if="form.asignacion_tipo === 'manual'">
      <label>Asignar a operador</label>
      <select v-model="form.operador_id">
        <option v-for="operador in operadores" :key="operador.id" :value="operador.id">
          {{ operador.nombre }}
        </option>
      </select>
    </div>

    <button type="submit">Guardar Lead</button>
  </form>
  <div v-if="successMessage" style="color: green; margin-top: 1rem;">
    {{ successMessage }}
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

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
