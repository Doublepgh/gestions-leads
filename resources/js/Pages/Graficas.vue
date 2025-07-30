<script setup>
import ChartComponent from '@/Components/ChartComponent.vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = usePage().props

// Computed para acceder a los datos
const abiertosVsCerrados = computed(() => props.abiertosVsCerrados)
const asignacionesPorOperador = computed(() => props.asignacionesPorOperador)

// Gráfico tipo doughnut
const chartData1 = {
  labels: abiertosVsCerrados.value.labels,
  datasets: [
    {
      label: 'Leads',
      data: abiertosVsCerrados.value.data,
      backgroundColor: ['#f59e0b', '#10b981'],
    },
  ],
}

const chartOptions1 = {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
  },
}

// Gráfico tipo barra
const chartData2 = {
  labels: asignacionesPorOperador.value.labels,
  datasets: [
    {
      label: 'Asignaciones por operador',
      data: asignacionesPorOperador.value.data,
      backgroundColor: '#3b82f6',
    },
  ],
}

const chartOptions2 = {
  responsive: true,
  plugins: {
    legend: {
      display: true,
    },
  },
}
</script>

<template>
  <div class="p-6 space-y-10">
    <div>
      <h2 class="text-lg font-bold mb-4">Leads Abiertos vs Cerrados</h2>
      <ChartComponent :chart-data="chartData1" :chart-options="chartOptions1" />
    </div>

    <div>
      <h2 class="text-lg font-bold mb-4">Asignaciones por Operador</h2>
      <ChartComponent :chart-data="chartData2" :chart-options="chartOptions2" />
    </div>
  </div>
</template>
