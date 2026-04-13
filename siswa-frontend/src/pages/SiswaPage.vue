<template>
  <div class="space-y-5">

    <!-- Header toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

      <!-- LEFT: Search + Filter -->
      <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">

        <!-- Search -->
        <div class="relative w-full sm:w-64">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
          </svg>
          <input
            v-model="siswaStore.filters.search"
            @input="onSearch"
            type="text"
            placeholder="Cari nama siswa..."
            class="form-input pl-9 w-full"
          />
        </div>

        <!-- Filter kelas -->
        <select
          v-model="siswaStore.filters.kelas"
          @change="onFilterKelas"
          class="form-input w-full sm:w-auto"
        >
          <option value="">Semua Kelas</option>
          <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
        </select>

      </div>

      <!-- RIGHT: Actions -->
      <div class="flex flex-wrap gap-2 sm:justify-end w-full sm:w-auto">

        <!-- Import -->
        <label class="btn-secondary cursor-pointer flex items-center justify-center gap-2 w-full sm:w-auto">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
          </svg>
          Import
          <input type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="handleImport" />
        </label>

        <!-- Export -->
        <button @click="handleExport" class="btn-secondary flex items-center justify-center gap-2 w-full sm:w-auto">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          Export
        </button>

        <!-- Tambah -->
        <button @click="openCreate" class="btn-primary flex items-center justify-center gap-2 w-full sm:w-auto">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Siswa
        </button>

      </div>
    </div>

    <!-- Toast -->
    <Transition name="fade">
      <div v-if="toast.show"
        class="fixed top-4 right-4 z-50 px-4 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2"
        :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path v-if="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ toast.msg }}
      </div>
    </Transition>

    <!-- Table -->
    <DataTable
      :columns="columns"
      :rows="siswaStore.list"
      :loading="siswaStore.loading"
      :meta="siswaStore.meta"
      @page-change="onPageChange"
    >
      <!-- Koordinat -->
      <template #cell-coordinate="{ row }">
        <span v-if="row.coordinate" class="font-mono text-xs text-slate-500">{{ row.coordinate }}</span>
        <span v-else class="text-slate-300">—</span>
      </template>

      <!-- Aksi -->
      <template #actions="{ row }">
        <div class="flex items-center justify-end gap-1.5">
          <button
            @click="openMap(row)"
            title="Lihat di Peta"
            class="p-1.5 rounded-lg hover:bg-indigo-50 text-indigo-500 hover:text-indigo-700 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </button>
          <button
            @click="openEdit(row)"
            class="p-1.5 rounded-lg hover:bg-amber-50 text-amber-500 hover:text-amber-700 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
          </button>
          <button
            @click="confirmDelete(row)"
            class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 hover:text-red-600 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
          </button>
        </div>
      </template>
    </DataTable>

    <!-- ── Modal Create/Edit ── -->
    <ModalForm
      v-model="showModal"
      :title="editTarget ? 'Edit Siswa' : 'Tambah Siswa'"
      :loading="saving"
      @submit="handleSubmit"
    >
      <div class="space-y-4">
        <!-- Nama -->
        <div>
          <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
          <input v-model="form.nama" type="text" placeholder="Nama siswa" class="form-input" :class="errors.nama ? 'border-red-400' : ''" />
          <p v-if="errors.nama" class="form-error">{{ errors.nama }}</p>
        </div>
        <!-- Kelas -->
        <div>
          <label class="form-label">Kelas <span class="text-red-500">*</span></label>
          <input v-model="form.kelas" type="text" placeholder="Contoh: 7A" class="form-input" :class="errors.kelas ? 'border-red-400' : ''" />
          <p v-if="errors.kelas" class="form-error">{{ errors.kelas }}</p>
        </div>
        <!-- Alamat -->
        <div>
          <label class="form-label">Alamat <span class="text-red-500">*</span></label>
          <textarea v-model="form.alamat" rows="2" placeholder="Alamat lengkap" class="form-input resize-none" :class="errors.alamat ? 'border-red-400' : ''" />
          <p v-if="errors.alamat" class="form-error">{{ errors.alamat }}</p>
        </div>
        <!-- Koordinat -->
        <div>
          <label class="form-label">Koordinat <span class="text-slate-400 font-normal">(opsional)</span></label>
          <input v-model="form.coordinate" type="text" placeholder="-7.7956,110.3695" class="form-input font-mono text-xs" :class="errors.coordinate ? 'border-red-400' : ''" />
          <p class="text-xs text-slate-400 mt-1">Format: lat,lng — contoh: -7.7956,110.3695</p>
          <p v-if="errors.coordinate" class="form-error">{{ errors.coordinate }}</p>
        </div>
      </div>
    </ModalForm>

    <!-- ── Modal Delete Confirm ── -->
    <ModalForm
      v-model="showDeleteModal"
      title="Hapus Siswa"
      submit-label="Hapus"
      :loading="deleting"
      max-width="400px"
      @submit="doDelete"
    >
      <p class="text-slate-600 text-sm">
        Yakin ingin menghapus siswa <strong class="text-slate-800">{{ deleteTarget?.nama }}</strong>?
        Data yang sudah dihapus tidak dapat dikembalikan.
      </p>
    </ModalForm>

    <!-- ── Modal Map Single Siswa ── -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showMapModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showMapModal = false" />
          <div class="modal-box relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
              <h3 class="font-semibold text-slate-800">Lokasi: {{ mapTarget?.nama }}</h3>
              <button @click="showMapModal = false" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            <div class="p-4">
              <p v-if="!mapTarget?.lat" class="text-slate-400 text-sm text-center py-6">Koordinat belum diisi untuk siswa ini.</p>
              <MapView
                v-else
                map-id="siswa-detail-map"
                :markers="mapTarget ? [mapTarget] : []"
                title=""
                height="300px"
              />
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useSiswaStore } from '@/stores/siswa'
import DataTable from '@/components/DataTable.vue'
import ModalForm from '@/components/ModalForm.vue'
import MapView   from '@/components/MapView.vue'

const siswaStore = useSiswaStore()

// ── Table columns ──
const columns = [
  { key: 'id',         label: 'ID',          class: 'w-12' },
  { key: 'nama',       label: 'Nama' },
  { key: 'kelas',      label: 'Kelas',       class: 'w-20' },
  { key: 'alamat',     label: 'Alamat' },
  { key: 'coordinate', label: 'Koordinat' },
]

// ── Filters ──
const kelasList = ['7A','7B','7C','8A','8B','8C','9A','9B','9C']

let searchTimeout = null
function onSearch() {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    siswaStore.filters.page = 1
    siswaStore.fetchAll()
  }, 400)
}

function onFilterKelas() {
  siswaStore.filters.page = 1
  siswaStore.fetchAll()
}

function onPageChange(page) {
  siswaStore.filters.page = page
  siswaStore.fetchAll()
}

// ── Toast ──
const toast = reactive({ show: false, msg: '', type: 'success' })
function showToast(msg, type = 'success') {
  toast.msg  = msg
  toast.type = type
  toast.show = true
  setTimeout(() => (toast.show = false), 3000)
}

// ── Create / Edit ──
const showModal  = ref(false)
const editTarget = ref(null)
const saving     = ref(false)
const errors     = reactive({})

const form = reactive({ nama: '', kelas: '', alamat: '', coordinate: '' })

function openCreate() {
  editTarget.value = null
  Object.assign(form, { nama: '', kelas: '', alamat: '', coordinate: '' })
  Object.keys(errors).forEach(k => delete errors[k])
  showModal.value = true
}

function openEdit(row) {
  editTarget.value = row
  Object.assign(form, {
    nama:       row.nama,
    kelas:      row.kelas,
    alamat:     row.alamat,
    coordinate: row.coordinate || '',
  })
  Object.keys(errors).forEach(k => delete errors[k])
  showModal.value = true
}

async function handleSubmit() {
  saving.value = true
  Object.keys(errors).forEach(k => delete errors[k])
  try {
    if (editTarget.value) {
      await siswaStore.update(editTarget.value.id, form)
      showToast('Data siswa berhasil diperbarui.')
    } else {
      await siswaStore.create(form)
      showToast('Siswa berhasil ditambahkan.')
    }
    showModal.value = false
  } catch (e) {
    const errs = e.response?.data?.errors
    if (errs) Object.entries(errs).forEach(([k, v]) => (errors[k] = v[0]))
    else showToast(e.response?.data?.message || 'Terjadi kesalahan.', 'error')
  } finally {
    saving.value = false
  }
}

// ── Delete ──
const showDeleteModal = ref(false)
const deleteTarget    = ref(null)
const deleting        = ref(false)

function confirmDelete(row) {
  deleteTarget.value  = row
  showDeleteModal.value = true
}

async function doDelete() {
  deleting.value = true
  try {
    await siswaStore.remove(deleteTarget.value.id)
    showDeleteModal.value = false
    showToast('Siswa berhasil dihapus.')
  } catch (_) {
    showToast('Gagal menghapus data.', 'error')
  } finally {
    deleting.value = false
  }
}

// ── Import / Export ──
async function handleImport(e) {
  const file = e.target.files[0]
  if (!file) return
  try {
    await siswaStore.importFile(file)
    showToast('Import berhasil.')
  } catch (err) {
    showToast(err.response?.data?.message || 'Import gagal.', 'error')
  }
  e.target.value = ''
}

async function handleExport() {
  try {
    await siswaStore.exportFile()
    showToast('File Excel berhasil diunduh.')
  } catch (_) {
    showToast('Export gagal.', 'error')
  }
}

// ── Map Modal ──
const showMapModal = ref(false)
const mapTarget    = ref(null)

function openMap(row) {
  mapTarget.value  = row
  showMapModal.value = true
}

onMounted(() => siswaStore.fetchAll())
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.modal-enter-active, .modal-leave-active { transition: opacity .2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
