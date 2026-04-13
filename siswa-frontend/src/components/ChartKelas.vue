<template>
  <div class="card p-5">
    <h2 class="text-sm font-semibold text-slate-700 mb-4">Rata-rata Nilai per Kelas</h2>

    <div v-if="loading" class="h-56 flex items-center justify-center">
      <div class="animate-spin w-6 h-6 border-2 border-indigo-500 border-t-transparent rounded-full" />
    </div>

    <div v-else-if="!data.length" class="h-56 flex items-center justify-center text-slate-400 text-sm">
      Belum ada data nilai.
    </div>

    <div v-else class="relative h-56">
      <canvas ref="canvasRef" />
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  data:    { type: Array,   default: () => [] },
  loading: { type: Boolean, default: false },
})

const canvasRef     = ref(null)
let   chartInstance = null

const COLORS = ['#6366f1','#22c55e','#f59e0b','#ef4444','#8b5cf6','#14b8a6','#f97316']

function buildChart() {
  if (!canvasRef.value || !props.data.length) return
  if (chartInstance) { chartInstance.destroy(); chartInstance = null }

  chartInstance = new Chart(canvasRef.value, {
    type: 'bar',
    data: {
      labels: props.data.map(d => d.kelas),
      datasets: [{
        label: 'Rata-rata Nilai',
        data:  props.data.map(d => d.rata_rata),
        backgroundColor: props.data.map((_, i) => COLORS[i % COLORS.length]),
        borderRadius: 6,
        borderSkipped: false,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => ` ${ctx.parsed.y} (${props.data[ctx.dataIndex]?.nilai_huruf})`,
          },
        },
      },
      scales: {
        y: {
          min: 0,
          max: 100,
          grid: { color: '#f1f5f9' },
          ticks: { font: { size: 11 }, color: '#94a3b8' },
        },
        x: {
          grid: { display: false },
          ticks: { font: { size: 11 }, color: '#64748b' },
        },
      },
    },
  })
}

watch(() => props.data, async () => {
  await nextTick()
  buildChart()
}, { deep: true })

onMounted(async () => {
  await nextTick()
  buildChart()
})

onBeforeUnmount(() => {
  if (chartInstance) chartInstance.destroy()
})
</script>
