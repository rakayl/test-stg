<template>
  <div class="min-h-screen flex bg-slate-50">

    <!-- ── Sidebar ── -->
    <aside
      class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-slate-900 transition-transform duration-300 lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <!-- Logo -->
      <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-700/60">
        <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
          S
        </div>
        <span class="font-semibold text-white text-base tracking-tight">SiswaApp</span>
      </div>

      <!-- Nav Items -->
      <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        <RouterLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
          :class="route.path.startsWith(item.to)
            ? 'bg-indigo-600 text-white'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white'"
          @click="sidebarOpen = false"
        >
          <span class="w-4 h-4 shrink-0" v-html="item.icon" />
          {{ item.label }}
        </RouterLink>
      </nav>

      <!-- User info + logout -->
      <div class="px-4 py-4 border-t border-slate-700/60 space-y-3">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-indigo-500/30 flex items-center justify-center text-indigo-300 font-semibold text-sm shrink-0">
            {{ userInitial }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">{{ auth.user?.name }}</p>
            <p class="text-xs text-slate-400 truncate">{{ auth.user?.email }}</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-slate-400 hover:bg-slate-800 hover:text-white transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Keluar
        </button>
      </div>
    </aside>

    <!-- Mobile overlay -->
    <Transition name="fade">
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        @click="sidebarOpen = false"
      />
    </Transition>

    <!-- ── Main Content ── -->
    <div class="flex-1 flex flex-col min-h-screen lg:pl-64">
      <!-- Topbar -->
      <header class="sticky top-0 z-30 h-14 bg-white/90 backdrop-blur border-b border-slate-200 flex items-center gap-4 px-4 lg:px-6">
        <button
          class="lg:hidden p-1.5 rounded-lg hover:bg-slate-100 text-slate-600"
          @click="sidebarOpen = !sidebarOpen"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <h1 class="text-sm font-semibold text-slate-700">{{ pageTitle }}</h1>
      </header>

      <!-- Page -->
      <main class="flex-1 p-4 lg:p-6">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route  = useRoute()
const router = useRouter()
const auth   = useAuthStore()

const sidebarOpen = ref(false)

const userInitial = computed(() =>
  auth.user?.name ? auth.user.name.charAt(0).toUpperCase() : 'U'
)

const navItems = [
  {
    to: '/dashboard',
    label: 'Dashboard',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
  },
  {
    to: '/siswa',
    label: 'Data Siswa',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
  },
  {
    to: '/nilai',
    label: 'Data Nilai',
    icon: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
  },
]

const pageTitles = { '/dashboard': 'Dashboard', '/siswa': 'Data Siswa', '/nilai': 'Data Nilai' }
const pageTitle  = computed(() => {
  const key = Object.keys(pageTitles).find(k => route.path.startsWith(k))
  return key ? pageTitles[key] : 'SiswaApp'
})

async function handleLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>