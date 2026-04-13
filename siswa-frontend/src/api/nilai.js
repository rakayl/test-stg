import api from './axios'

export const nilaiApi = {
  getAll  : (params)   => api.get('/nilais', { params }),
  getById : (id)       => api.get(`/nilais/${id}`),
  create  : (data)     => api.post('/nilais', data),
  update  : (id, data) => api.put(`/nilais/${id}`, data),
  remove  : (id)       => api.delete(`/nilais/${id}`),
  import  : (file)     => {
    const form = new FormData()
    form.append('file', file)
    return api.post('/nilais/import', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
  export  : ()         => api.get('/nilais/export', { responseType: 'blob' }),
}