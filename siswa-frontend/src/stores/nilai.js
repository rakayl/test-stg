import { defineStore } from 'pinia'
import { ref } from 'vue'
import { nilaiApi } from '@/api/nilai'

export const useNilaiStore = defineStore('nilai', () => {
  const list    = ref([])
  const meta    = ref({ total: 0, last_page: 1, current_page: 1 })
  const loading = ref(false)
  const filters = ref({ page: 1, siswa_id: '', mapel: '', kelas: '' })

  async function fetchAll() {
    loading.value = true
    try {
      const params = { page: filters.value.page }
      if (filters.value.siswa_id) params.siswa_id = filters.value.siswa_id
      if (filters.value.mapel)    params.mapel    = filters.value.mapel
      if (filters.value.kelas)    params.kelas    = filters.value.kelas

      const res  = await nilaiApi.getAll(params)
      list.value = res.data.data
      meta.value = res.data.meta || {}
    } finally {
      loading.value = false
    }
  }

  async function create(data) {
    await nilaiApi.create(data)
    await fetchAll()
  }

  async function update(id, data) {
    await nilaiApi.update(id, data)
    await fetchAll()
  }

  async function remove(id) {
    await nilaiApi.remove(id)
    await fetchAll()
  }

  async function importFile(file) {
    await nilaiApi.import(file)
    await fetchAll()
  }

  async function exportFile() {
    const res  = await nilaiApi.export()
    const url  = URL.createObjectURL(new Blob([res.data]))
    const a    = document.createElement('a')
    a.href     = url
    a.download = 'nilai.xlsx'
    a.click()
    URL.revokeObjectURL(url)
  }

  return { list, meta, loading, filters, fetchAll, create, update, remove, importFile, exportFile }
})