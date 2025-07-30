<template>
  <div>
    <canvas ref="myChart"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  chartData: Object,
  chartOptions: Object,
})

const myChart = ref(null)

onMounted(() => {
  new Chart(myChart.value, {
    type: 'bar', // Cambia a 'line', 'pie', 'doughnut', etc. según lo necesites
    data: props.chartData,
    options: props.chartOptions,
  })
})
</script>