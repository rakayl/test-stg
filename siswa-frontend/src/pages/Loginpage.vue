<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-4">
    <div class="w-full max-w-sm">

      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-indigo-500/30">
          S
        </div>
        <h1 class="text-2xl font-bold text-white">SiswaApp</h1>
        <p class="text-slate-400 text-sm mt-1">Masuk ke akun Anda</p>
      </div>

      <!-- Card -->
      <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 shadow-2xl">

        <!-- Error alert -->
        <div v-if="errorMsg" class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm flex items-start gap-2">
          <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ errorMsg }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="admin@example.com"
              required
              autocomplete="email"
              class="w-full px-3 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white
                     placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                     focus:border-transparent transition"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPass ? 'text' : 'password'"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                class="w-full px-3 py-2.5 pr-10 rounded-lg bg-white/5 border border-white/10 text-white
                       placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                       focus:border-transparent transition"
              />
              <button
                type="button"
                @click="showPass = !showPass"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path v-if="!showPass" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-medium
                   text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex
                   items-center justify-center gap-2 mt-2"
          >
            <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            {{ loading ? 'Masuk...' : 'Masuk' }}
          </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-400">
          Belum punya akun?
          <RouterLink to="/register" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
            Daftar sekarang
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth     = useAuthStore()
const router   = useRouter()
const loading  = ref(false)
const errorMsg = ref('')
const showPass = ref(false)

const form = reactive({ email: '', password: '' })

async function handleLogin() {
  loading.value  = true
  errorMsg.value = ''
  try {
    await auth.login(form)
    router.push('/dashboard')
  } catch (e) {
    const errs = e.response?.data?.errors
    errorMsg.value = errs
      ? Object.values(errs).flat().join(' ')
      : (e.response?.data?.message || 'Login gagal. Periksa email dan password.')
  } finally {
    loading.value = false
  }
}
</script>
