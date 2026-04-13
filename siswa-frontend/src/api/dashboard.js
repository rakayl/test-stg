import api from './axios'

export const dashboardApi = {
  stats : () => api.get('/dashboard/stats'),
  chart : () => api.get('/dashboard/chart'),
}