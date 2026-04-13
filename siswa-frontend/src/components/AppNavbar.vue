<template>
  <header class="sticky top-0 z-30 h-14 bg-white/90 backdrop-blur border-b border-slate-200 flex items-center gap-4 px-4 lg:px-6">
    <!-- Hamburger (mobile) -->
    <button
      class="lg:hidden p-1.5 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors"
      @click="$emit('toggle-sidebar')"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Page title -->
    <h1 class="text-sm font-semibold text-slate-700 flex-1">{{ title }}</h1>

    <!-- Right slot -->
    <slot name="right" />
  </header>
</template>

<script setup>
defineProps({ title: { type: String, default: '' } })
defineEmits(['toggle-sidebar'])
</script>
