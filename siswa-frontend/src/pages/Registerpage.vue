<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-4">
    <div class="w-full max-w-sm">

      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-indigo-500/30">
          S
        </div>
        <h1 class="text-2xl font-bold text-white">Daftar Akun</h1>
        <p class="text-slate-400 text-sm mt-1">Buat akun baru SiswaApp</p>
      </div>

      <!-- Card -->
      <div class="bg-white/5 backdrop-blur border border-white/10 rounded-2xl p-6 shadow-2xl">

        <!-- General error -->
        <div v-if="errors.general" class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
          {{ errors.general }}
        </div>

        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="John Doe"
              required
              class="w-full px-3 py-2.5 rounded-lg bg-white/5 border text-white placeholder-slate-500
                     text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
              :class="errors.name ? 'border-red-500/60' : 'border-white/10'"
            />
            <p v-if="errors.name" class="mt-1 text-xs text-red-400">{{ errors.name }}</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
            <input
              v-model="form.email"
              type="email"
              placeholder="admin@example.com"
              required
              class="w-full px-3 py-2.5 rounded-lg bg-white/5 border text-white placeholder-slate-500
                     text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
              :class="errors.email ? 'border-red-500/60' : 'border-white/10'"
            />
            <p v-if="errors.email" class="mt-1 text-xs text-red-400">{{ errors.email }}</p>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
            <input
              v-model="form.password"
              type="password"
              placeholder="Min. 8 karakter"
              required
              class="w-full px-3 py-2.5 rounded-lg bg-white/5 border text-white placeholder-slate-500
                     text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
              :class="errors.password ? 'border-red-500/60' : 'border-white/10'"
            />
            <p v-if="errors.password" class="mt-1 text-xs text-red-400">{{ errors.password }}</p>
          </div>

          <!-- Konfirmasi Password -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Password</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              placeholder="Ulangi password"
              required
              class="w-full px-3 py-2.5 rounded-lg bg-white/5 border border-white/10 text-white
                     placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500
                     focus:border-transparent transition"
            />
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
            {{ loading ? 'Mendaftar...' : 'Daftar' }}
          </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-400">
          Sudah punya akun?
          <RouterLink to="/login" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
            Masuk
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

const auth    = useAuthStore()
const router  = useRouter()
const loading = ref(false)
const errors  = reactive({})

const form = reactive({
  name: '', email: '', password: '', password_confirmation: '',
})

async function handleRegister() {
  loading.value = true
  Object.keys(errors).forEach(k => delete errors[k])
  try {
    await auth.register(form)
    router.push('/dashboard')
  } catch (e) {
    const errs = e.response?.data?.errors
    if (errs) {
      Object.entries(errs).forEach(([k, v]) => (errors[k] = v[0]))
    } else {
      errors.general = e.response?.data?.message || 'Registrasi gagal.'
    }
  } finally {
    loading.value = false
  }
}
</script>
