import { defineStore } from 'pinia'
import { ref } from 'vue'
import { siswaApi } from '@/api/siswa'

export const useSiswaStore = defineStore('siswa', () => {
  const list    = ref([])
  const meta    = ref({ total: 0, last_page: 1, current_page: 1 })
  const loading = ref(false)
  const filters = ref({ page: 1, search: '', kelas: '' })

  async function fetchAll() {
    loading.value = true
    try {
      const params = { page: filters.value.page }
      if (filters.value.search) params.search = filters.value.search
      if (filters.value.kelas)  params.kelas  = filters.value.kelas

      const res  = await siswaApi.getAll(params)
      list.value = res.data.data
      meta.value = res.data.meta || {}
    } finally {
      loading.value = false
    }
  }

  async function create(data) {
    await siswaApi.create(data)
    await fetchAll()
  }

  async function update(id, data) {
    await siswaApi.update(id, data)
    await fetchAll()
  }

  async function remove(id) {
    await siswaApi.remove(id)
    await fetchAll()
  }

  async function importFile(file) {
    await siswaApi.import(file)
    await fetchAll()
  }

  async function exportFile() {
    const res  = await siswaApi.export()
    const url  = URL.createObjectURL(new Blob([res.data]))
    const a    = document.createElement('a')
    a.href     = url
    a.download = 'siswa.xlsx'
    a.click()
    URL.revokeObjectURL(url)
  }

  return { list, meta, loading, filters, fetchAll, create, update, remove, importFile, exportFile }
})