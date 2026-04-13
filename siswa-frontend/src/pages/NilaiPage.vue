<template>
  <div class="space-y-5">

    <!-- Header toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

      <!-- LEFT: Filters -->
      <div class="flex flex-wrap gap-2">
        <select
          v-model="nilaiStore.filters.kelas"
          @change="onFilter"
          class="form-input w-full sm:w-auto"
        >
          <option value="">Semua Kelas</option>
          <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
        </select>

        <select
          v-model="nilaiStore.filters.mapel"
          @change="onFilter"
          class="form-input w-full sm:w-auto"
        >
          <option value="">Semua Mapel</option>
          <option v-for="m in mapelList" :key="m" :value="m">{{ m }}</option>
        </select>
      </div>

      <!-- RIGHT: Actions -->
      <div class="flex flex-wrap gap-2 sm:justify-end">

        <!-- Import -->
        <label class="btn-secondary cursor-pointer flex items-center justify-center gap-2 w-full sm:w-auto">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
          </svg>
          Import
          <input type="file" class="hidden" @change="handleImport" />
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
          Tambah Nilai
        </button>

      </div>
    </div>

    <!-- Toast -->
    <Transition name="fade">
      <div v-if="toast.show"
        class="fixed top-4 right-4 left-4 sm:left-auto z-50 px-4 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2"
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
      :rows="nilaiStore.list"
      :loading="nilaiStore.loading"
      :meta="nilaiStore.meta"
      @page-change="onPageChange"
    >
      <!-- Nilai angka + huruf badge -->
      <template #cell-nilai="{ row }">
        <div class="flex items-center gap-2">
          <span class="font-semibold text-slate-800">{{ row.nilai }}</span>
          <span :class="`badge-${row.nilai_huruf}`">{{ row.nilai_huruf }}</span>
        </div>
      </template>

      <!-- Nama siswa dari relasi -->
      <template #cell-siswa="{ row }">
        {{ row.siswa?.nama ?? `ID #${row.siswa_id}` }}
      </template>

      <!-- Aksi -->
      <template #actions="{ row }">
        <div class="flex items-center justify-end gap-1.5">
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
      :title="editTarget ? 'Edit Nilai' : 'Tambah Nilai'"
      :loading="saving"
      @submit="handleSubmit"
    >
      <div class="space-y-4">
        <!-- Siswa -->
        <div>
          <label class="form-label">Siswa <span class="text-red-500">*</span></label>
          <select v-model="form.siswa_id" class="form-input" :class="errors.siswa_id ? 'border-red-400' : ''">
            <option value="">-- Pilih Siswa --</option>
            <option v-for="s in siswaOptions" :key="s.id" :value="s.id">
              {{ s.nama }} ({{ s.kelas }})
            </option>
          </select>
          <p v-if="errors.siswa_id" class="form-error">{{ errors.siswa_id }}</p>
        </div>

        <!-- Kelas -->
        <div>
          <label class="form-label">Kelas <span class="text-red-500">*</span></label>
          <select v-model="form.kelas" class="form-input" :class="errors.kelas ? 'border-red-400' : ''">
            <option value="">-- Pilih Kelas --</option>
            <option v-for="k in kelasList" :key="k" :value="k">{{ k }}</option>
          </select>
          <p v-if="errors.kelas" class="form-error">{{ errors.kelas }}</p>
        </div>

        <!-- Mapel -->
        <div>
          <label class="form-label">Mata Pelajaran <span class="text-red-500">*</span></label>
          <select v-model="form.mapel" class="form-input" :class="errors.mapel ? 'border-red-400' : ''">
            <option value="">-- Pilih Mapel --</option>
            <option v-for="m in mapelList" :key="m" :value="m">{{ m }}</option>
          </select>
          <p v-if="errors.mapel" class="form-error">{{ errors.mapel }}</p>
        </div>

        <!-- Nilai -->
        <div>
          <label class="form-label">Nilai <span class="text-red-500">*</span></label>
          <input
            v-model.number="form.nilai"
            type="number"
            min="0"
            max="100"
            placeholder="0 – 100"
            class="form-input"
            :class="errors.nilai ? 'border-red-400' : ''"
          />
          <!-- Preview huruf -->
          <div v-if="form.nilai !== ''" class="mt-1.5 flex items-center gap-2 text-xs text-slate-500">
            Nilai huruf:
            <span :class="`badge-${nilaiHuruf}`">{{ nilaiHuruf }}</span>
          </div>
          <p v-if="errors.nilai" class="form-error">{{ errors.nilai }}</p>
        </div>
      </div>
    </ModalForm>

    <!-- ── Modal Delete ── -->
    <ModalForm
      v-model="showDeleteModal"
      title="Hapus Nilai"
      submit-label="Hapus"
      :loading="deleting"
      max-width="400px"
      @submit="doDelete"
    >
      <p class="text-slate-600 text-sm">
        Yakin ingin menghapus nilai
        <strong class="text-slate-800">{{ deleteTarget?.mapel }}</strong>
        milik
        <strong class="text-slate-800">{{ deleteTarget?.siswa?.nama }}</strong>?
      </p>
    </ModalForm>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useNilaiStore } from '@/stores/nilai'
import { siswaApi }      from '@/api/siswa'
import DataTable from '@/components/DataTable.vue'
import ModalForm from '@/components/ModalForm.vue'

const nilaiStore = useNilaiStore()

// ── Constants ──
const kelasList = ['7A','7B','7C','8A','8B','8C','9A','9B','9C']
const mapelList = ['Matematika','Bahasa Indonesia','Bahasa Inggris','IPA','IPS','PKN','Seni Budaya','Penjaskes']

const columns = [
  { key: 'id',    label: 'ID',    class: 'w-12' },
  { key: 'siswa', label: 'Nama Siswa' },
  { key: 'kelas', label: 'Kelas', class: 'w-20' },
  { key: 'mapel', label: 'Mata Pelajaran' },
  { key: 'nilai', label: 'Nilai' },
]

// ── Siswa dropdown ──
const siswaOptions = ref([])
async function loadSiswaOptions() {
  try {
    const res      = await siswaApi.getAll({ per_page: 999 })
    siswaOptions.value = res.data.data
  } catch (_) {}
}

// ── Filters ──
function onFilter() {
  nilaiStore.filters.page = 1
  nilaiStore.fetchAll()
}

function onPageChange(page) {
  nilaiStore.filters.page = page
  nilaiStore.fetchAll()
}

// ── Toast ──
const toast = reactive({ show: false, msg: '', type: 'success' })
function showToast(msg, type = 'success') {
  toast.msg  = msg
  toast.type = type
  toast.show = true
  setTimeout(() => (toast.show = false), 3000)
}

// ── Form ──
const showModal  = ref(false)
const editTarget = ref(null)
const saving     = ref(false)
const errors     = reactive({})

const form = reactive({ siswa_id: '', kelas: '', mapel: '', nilai: '' })

const nilaiHuruf = computed(() => {
  const n = Number(form.nilai)
  if (n >= 85) return 'A'
  if (n >= 70) return 'B'
  if (n >= 60) return 'C'
  return 'D'
})

function openCreate() {
  editTarget.value = null
  Object.assign(form, { siswa_id: '', kelas: '', mapel: '', nilai: '' })
  Object.keys(errors).forEach(k => delete errors[k])
  showModal.value = true
}

function openEdit(row) {
  editTarget.value = row
  Object.assign(form, {
    siswa_id: row.siswa_id,
    kelas:    row.kelas,
    mapel:    row.mapel,
    nilai:    row.nilai,
  })
  Object.keys(errors).forEach(k => delete errors[k])
  showModal.value = true
}

async function handleSubmit() {
  saving.value = true
  Object.keys(errors).forEach(k => delete errors[k])
  try {
    if (editTarget.value) {
      await nilaiStore.update(editTarget.value.id, form)
      showToast('Nilai berhasil diperbarui.')
    } else {
      await nilaiStore.create(form)
      showToast('Nilai berhasil ditambahkan.')
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
  deleteTarget.value    = row
  showDeleteModal.value = true
}

async function doDelete() {
  deleting.value = true
  try {
    await nilaiStore.remove(deleteTarget.value.id)
    showDeleteModal.value = false
    showToast('Nilai berhasil dihapus.')
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
    await nilaiStore.importFile(file)
    showToast('Import berhasil.')
  } catch (err) {
    showToast(err.response?.data?.message || 'Import gagal.', 'error')
  }
  e.target.value = ''
}

async function handleExport() {
  try {
    await nilaiStore.exportFile()
    showToast('File Excel berhasil diunduh.')
  } catch (_) {
    showToast('Export gagal.', 'error')
  }
}

onMounted(() => {
  nilaiStore.fetchAll()
  loadSiswaOptions()
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
