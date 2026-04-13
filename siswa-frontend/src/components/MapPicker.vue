<template>
  <div class="map-picker-wrapper">
    <!-- Peta -->
    <div ref="mapContainer" class="map-picker-canvas" />

    <!-- Input koordinat manual + tombol aksi -->
    <div class="map-picker-bar">
      <div class="coord-inputs">
        <div class="coord-field">
          <span class="coord-label">Lat</span>
          <input
            type="number"
            step="0.000001"
            :value="lat"
            @change="onInputChange('lat', $event.target.value)"
            placeholder="-7.7956"
            class="coord-input"
          />
        </div>
        <div class="coord-sep">,</div>
        <div class="coord-field">
          <span class="coord-label">Lng</span>
          <input
            type="number"
            step="0.000001"
            :value="lng"
            @change="onInputChange('lng', $event.target.value)"
            placeholder="110.3695"
            class="coord-input"
          />
        </div>
      </div>

      <div class="coord-actions">
        <!-- Gunakan lokasi saya -->
        <button type="button" class="btn-gps" :class="{ loading: gpsLoading }" @click="useMyLocation" :title="'Pakai lokasi saya'">
          <svg v-if="!gpsLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2M2 12h2m16 0h2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M4.93 19.07l1.41-1.41m11.32-11.32 1.41-1.41"/>
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
          </svg>
        </button>
        <!-- Reset -->
        <button type="button" class="btn-reset" @click="resetCoord" title="Hapus koordinat">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    

    <p v-if="gpsError" class="map-picker-error">{{ gpsError }}</p>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon   from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl:       markerIcon,
  shadowUrl:     markerShadow,
})

// ── Props & Emits ──────────────────────────────────────────────────────────
const props = defineProps({
  // Koordinat awal dalam format "lat,lng" (sama seperti form.coordinate)
  modelValue: { type: String, default: '' },
  height:     { type: String, default: '240px' },
})

const emit = defineEmits(['update:modelValue'])

// ── State ──────────────────────────────────────────────────────────────────
const mapContainer = ref(null)
const gpsLoading   = ref(false)
const gpsError     = ref('')

let leafletMap = null
let pinMarker  = null

// Parsed koordinat saat ini
const lat = ref(null)
const lng = ref(null)

// Parse prop awal
function parseModelValue(val) {
  if (!val) { lat.value = null; lng.value = null; return }
  const parts = String(val).split(',')
  const la = parseFloat(parts[0])
  const lo = parseFloat(parts[1])
  if (!isNaN(la) && !isNaN(lo)) { lat.value = la; lng.value = lo }
  else { lat.value = null; lng.value = null }
}

// Emit ke parent sebagai "lat,lng" string
function emitCoord() {
  if (lat.value !== null && lng.value !== null) {
    emit('update:modelValue', `${lat.value.toFixed(6)},${lng.value.toFixed(6)}`)
  } else {
    emit('update:modelValue', '')
  }
}

// ── Leaflet setup ──────────────────────────────────────────────────────────
function initMap() {
  if (!mapContainer.value) return

  const center = (lat.value !== null && lng.value !== null)
    ? [lat.value, lng.value]
    : [-7.7956, 110.3695]

  leafletMap = L.map(mapContainer.value, { zoomControl: true }).setView(center, lat.value !== null ? 15 : 12)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(leafletMap)

  // Taruh pin kalau sudah ada koordinat
  if (lat.value !== null && lng.value !== null) {
    placePin(lat.value, lng.value)
  }

  // Klik peta → set pin
  leafletMap.on('click', (e) => {
    lat.value = parseFloat(e.latlng.lat.toFixed(6))
    lng.value = parseFloat(e.latlng.lng.toFixed(6))
    placePin(lat.value, lng.value)
    emitCoord()
  })
}

function placePin(la, lo) {
  if (pinMarker) {
    pinMarker.setLatLng([la, lo])
  } else {
    pinMarker = L.marker([la, lo], { draggable: true })
      .addTo(leafletMap)

    // Drag selesai → update koordinat
    pinMarker.on('dragend', (e) => {
      const pos = e.target.getLatLng()
      lat.value = parseFloat(pos.lat.toFixed(6))
      lng.value = parseFloat(pos.lng.toFixed(6))
      emitCoord()
    })
  }
}

// ── Input manual ──────────────────────────────────────────────────────────
function onInputChange(field, val) {
  const num = parseFloat(val)
  if (isNaN(num)) return
  if (field === 'lat') lat.value = num
  if (field === 'lng') lng.value = num

  if (lat.value !== null && lng.value !== null) {
    placePin(lat.value, lng.value)
    leafletMap.setView([lat.value, lng.value], leafletMap.getZoom())
    emitCoord()
  }
}

// ── GPS ───────────────────────────────────────────────────────────────────
function useMyLocation() {
  if (!navigator.geolocation) {
    gpsError.value = 'Browser tidak mendukung geolocation.'
    return
  }
  gpsLoading.value = true
  gpsError.value   = ''
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      lat.value = parseFloat(pos.coords.latitude.toFixed(6))
      lng.value = parseFloat(pos.coords.longitude.toFixed(6))
      placePin(lat.value, lng.value)
      leafletMap.setView([lat.value, lng.value], 16)
      emitCoord()
      gpsLoading.value = false
    },
    (err) => {
      gpsError.value   = 'Gagal mendapatkan lokasi: ' + err.message
      gpsLoading.value = false
    },
    { timeout: 10000 }
  )
}

// ── Reset ─────────────────────────────────────────────────────────────────
function resetCoord() {
  lat.value = null
  lng.value = null
  if (pinMarker) { pinMarker.remove(); pinMarker = null }
  emit('update:modelValue', '')
}

// ── Sync dari parent (saat edit siswa berbeda) ─────────────────────────────
watch(() => props.modelValue, (val) => {
  parseModelValue(val)
  if (!leafletMap) return
  if (lat.value !== null && lng.value !== null) {
    placePin(lat.value, lng.value)
    leafletMap.setView([lat.value, lng.value], 15)
  } else {
    if (pinMarker) { pinMarker.remove(); pinMarker = null }
  }
}, { immediate: false })

// ── Lifecycle ─────────────────────────────────────────────────────────────
onMounted(async () => {
  parseModelValue(props.modelValue)
  await nextTick()
  initMap()
})

onBeforeUnmount(() => {
  if (leafletMap) { leafletMap.remove(); leafletMap = null }
})
</script>

<style scoped>
.map-picker-wrapper {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.map-picker-canvas {
  width: 100%;
  border-radius: 10px;
  overflow: hidden;
  border: 1.5px solid #e2e8f0;
  height: v-bind(height);
  background: #f1f5f9;
  z-index: 0;
}

/* ── Bar bawah peta ── */
.map-picker-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 10px;
  padding: 6px 10px;
}

.coord-inputs {
  display: flex;
  align-items: center;
  gap: 4px;
  flex: 1;
}

.coord-field {
  display: flex;
  align-items: center;
  gap: 4px;
  flex: 1;
}

.coord-label {
  font-size: 11px;
  font-weight: 600;
  color: #94a3b8;
  white-space: nowrap;
  letter-spacing: .04em;
  text-transform: uppercase;
}

.coord-input {
  flex: 1;
  background: white;
  border: 1.5px solid #e2e8f0;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 12px;
  font-family: 'JetBrains Mono', monospace;
  color: #334155;
  width: 100%;
  outline: none;
  transition: border-color .15s;
}
.coord-input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.1); }

.coord-sep {
  font-size: 14px;
  color: #cbd5e1;
  font-weight: 600;
  margin: 0 2px;
}

.coord-actions {
  display: flex;
  gap: 4px;
  flex-shrink: 0;
}

.btn-gps, .btn-reset {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1.5px solid #e2e8f0;
  background: white;
  color: #64748b;
  cursor: pointer;
  transition: all .15s;
  flex-shrink: 0;
}
.btn-gps:hover  { border-color: #6366f1; color: #6366f1; background: #eef2ff; }
.btn-reset:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
.btn-gps.loading { opacity: .6; cursor: not-allowed; }

.map-picker-hint {
  font-size: 11px;
  color: #94a3b8;
  line-height: 1.4;
}

.map-picker-error {
  font-size: 12px;
  color: #ef4444;
}
</style>
