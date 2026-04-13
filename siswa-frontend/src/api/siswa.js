import api from './axios'

export const siswaApi = {
  getAll  : (params)     => api.get('/siswas', { params }),
  getById : (id)         => api.get(`/siswas/${id}`),
  create  : (data)       => api.post('/siswas', data),
  update  : (id, data)   => api.put(`/siswas/${id}`, data),
  remove  : (id)         => api.delete(`/siswas/${id}`),
  import  : (file)       => {
    const form = new FormData()
    form.append('file', file)
    return api.post('/siswas/import', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
  export  : ()           => api.get('/siswas/export', { responseType: 'blob' }),
}