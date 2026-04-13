<template>
  <div class="space-y-6">

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div
        v-for="card in statCards"
        :key="card.label"
        class="card p-4"
      >
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ card.label }}</span>
          <div :class="`w-9 h-9 rounded-xl flex items-center justify-center text-lg ${card.bg}`">
            {{ card.emoji }}
          </div>
        </div>
        <div class="h-8 flex items-end">
          <span v-if="statsLoading" class="w-16 h-7 bg-slate-200 rounded animate-pulse inline-block" />
          <span v-else class="text-2xl font-bold text-slate-800">{{ card.value }}</span>
        </div>
      </div>
    </div>

    <!-- Chart + Map -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <ChartKelas :data="chartData" :loading="chartLoading" />
      <MapView map-id="dashboard-map" :markers="mapMarkers" title="Lokasi Rumah Siswa" height="260px" />
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { dashboardApi } from '@/api/dashboard'
import { siswaApi }     from '@/api/siswa'
import ChartKelas from '@/components/ChartKelas.vue'
import MapView    from '@/components/MapView.vue'

const stats        = ref({})
const chartData    = ref([])
const allSiswa     = ref([])
const statsLoading = ref(true)
const chartLoading = ref(true)

const statCards = computed(() => [
  { label: 'Total Siswa',     emoji: '👨‍🎓', bg: 'bg-indigo-50',  value: stats.value.jumlah_siswa    ?? 0 },
  { label: 'Total Kelas',     emoji: '🏫',  bg: 'bg-emerald-50', value: stats.value.jumlah_kelas    ?? 0 },
  { label: 'Mata Pelajaran',  emoji: '📚',  bg: 'bg-amber-50',   value: stats.value.jumlah_mapel    ?? 0 },
  { label: 'Rata-rata Nilai', emoji: '📊',  bg: 'bg-rose-50',    value: stats.value.rata_rata_nilai ?? 0 },
])

const mapMarkers = computed(() =>
  allSiswa.value.filter(s => s.lat && s.lng)
)

async function loadStats() {
  statsLoading.value = true
  try {
    const res   = await dashboardApi.stats()
    stats.value = res.data.data
  } finally {
    statsLoading.value = false
  }
}

async function loadChart() {
  chartLoading.value = true
  try {
    const res       = await dashboardApi.chart()
    chartData.value = res.data.data
  } finally {
    chartLoading.value = false
  }
}

async function loadSiswaForMap() {
  try {
    const res       = await siswaApi.getAll({ per_page: 999 })
    allSiswa.value  = res.data.data
  } catch (_) {}
}

onMounted(() => {
  loadStats()
  loadChart()
  loadSiswaForMap()
})
</script>
