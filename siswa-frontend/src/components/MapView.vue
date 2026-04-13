<template>
  <div class="card p-5">
    <h2 v-if="title" class="text-sm font-semibold text-slate-700 mb-4">{{ title }}</h2>
    <div ref="mapContainer" class="rounded-lg overflow-hidden bg-slate-100" :style="{ height }" />
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Fix Leaflet default marker icon (broken dengan bundler)
import markerIcon2x   from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon     from 'leaflet/dist/images/marker-icon.png'
import markerShadow   from 'leaflet/dist/images/marker-shadow.png'

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl:       markerIcon,
  shadowUrl:     markerShadow,
})

const props = defineProps({
  title:   { type: String, default: 'Peta Lokasi Siswa' },
  height:  { type: String, default: '280px' },
  markers: { type: Array,  default: () => [] },
  // markers: [{ lat, lng, nama, kelas, alamat }]
})

const mapContainer = ref(null)
let leafletMap  = null
let markerLayer = null

function initMap() {
  if (!mapContainer.value) return

  if (leafletMap) {
    leafletMap.remove()
    leafletMap  = null
    markerLayer = null
  }

  // Titik tengah default: Yogyakarta
  const center = props.markers.length
    ? [props.markers[0].lat, props.markers[0].lng]
    : [-7.7956, 110.3695]

  leafletMap  = L.map(mapContainer.value).setView(center, 13)
  markerLayer = L.layerGroup().addTo(leafletMap)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    maxZoom: 18,
  }).addTo(leafletMap)

  renderMarkers()
}

function renderMarkers() {
  if (!leafletMap || !markerLayer) return
  markerLayer.clearLayers()

  props.markers.forEach((s) => {
    const lat = parseFloat(s.lat)
    const lng = parseFloat(s.lng)
    if (isNaN(lat) || isNaN(lng)) return

    L.marker([lat, lng])
      .addTo(markerLayer)
      .bindPopup(
        `<div style="font-family:sans-serif;min-width:140px">
          <p style="font-weight:600;margin:0 0 2px">${s.nama ?? ''}</p>
          <p style="margin:0;color:#64748b;font-size:12px">${s.kelas ?? ''}</p>
          <p style="margin:4px 0 0;font-size:12px">${s.alamat ?? ''}</p>
        </div>`
      )
      .openPopup()
  })

  // Fit bounds kalau ada lebih dari 1 marker
  if (props.markers.length > 1) {
    const bounds = props.markers
      .map(s => [parseFloat(s.lat), parseFloat(s.lng)])
      .filter(([la, lo]) => !isNaN(la) && !isNaN(lo))
    if (bounds.length) leafletMap.fitBounds(bounds, { padding: [30, 30] })
  }
}

watch(() => props.markers, renderMarkers, { deep: true })

onMounted(async () => {
  await nextTick()
  initMap()
})

onBeforeUnmount(() => {
  if (leafletMap) { leafletMap.remove(); leafletMap = null }
})
</script>