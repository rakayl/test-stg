import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authApi } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(null)
  const token = ref(localStorage.getItem('token') || null)

  const isLoggedIn = computed(() => !!token.value)

  async function register(data) {
    const res = await authApi.register(data)
    _setSession(res.data)
    return res.data
  }

  async function login(data) {
    const res = await authApi.login(data)
    _setSession(res.data)
    return res.data
  }

  async function logout() {
    try { await authApi.logout() } catch (_) {}
    _clearSession()
  }

  async function fetchMe() {
    const res = await authApi.me()
    user.value = res.data.user
  }

  function _setSession(data) {
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
  }

  function _clearSession() {
    token.value = null
    user.value  = null
    localStorage.removeItem('token')
  }

  return { user, token, isLoggedIn, register, login, logout, fetchMe }
})