<template>
  <div class="card overflow-hidden">
    <!-- Loading -->
    <div v-if="loading" class="p-10 flex justify-center">
      <div class="animate-spin w-7 h-7 border-2 border-indigo-500 border-t-transparent rounded-full" />
    </div>

    <template v-else>
      <!-- Empty -->
      <div v-if="!rows.length" class="p-10 text-center text-slate-400 text-sm">
        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Tidak ada data ditemukan.
      </div>

      <template v-else>
        <!-- ── Desktop Table (md+) ── -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200">
                <th
                  v-for="col in columns" :key="col.key"
                  class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap"
                  :class="col.class || ''"
                >{{ col.label }}</th>
                <th v-if="$slots.actions" class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="(row, i) in rows" :key="row.id || i" class="hover:bg-slate-50/60 transition-colors">
                <td
                  v-for="col in columns" :key="col.key"
                  class="px-4 py-3 text-slate-700"
                  :class="col.tdClass || ''"
                >
                  <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
                    {{ row[col.key] ?? '-' }}
                  </slot>
                </td>
                <td v-if="$slots.actions" class="px-4 py-3 text-right whitespace-nowrap">
                  <slot name="actions" :row="row" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── Mobile Cards (< md) ── -->
        <div class="md:hidden divide-y divide-slate-100">
          <div
            v-for="(row, i) in rows" :key="row.id || i"
            class="p-4 space-y-2 hover:bg-slate-50/60 transition-colors"
          >
            <!-- Baris tiap kolom sebagai label:value -->
            <div
              v-for="col in columns" :key="col.key"
              class="flex items-start justify-between gap-3 text-sm"
            >
              <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide shrink-0 pt-0.5 w-28">
                {{ col.label }}
              </span>
              <span class="text-slate-700 text-right flex-1 min-w-0 break-words">
                <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
                  {{ row[col.key] ?? '-' }}
                </slot>
              </span>
            </div>
            <!-- Aksi di bawah -->
            <div v-if="$slots.actions" class="pt-2 flex justify-end border-t border-slate-100">
              <slot name="actions" :row="row" />
            </div>
          </div>
        </div>
      </template>

      <!-- ── Pagination ── -->
      <div
        v-if="meta && meta.last_page > 1"
        class="flex flex-col sm:flex-row items-center justify-between gap-2 px-4 py-3 border-t border-slate-100 text-sm"
      >
        <span class="text-slate-500 text-xs">
          Menampilkan {{ meta.from }}–{{ meta.to }} dari {{ meta.total }} data
        </span>
        <div class="flex gap-1 flex-wrap justify-center">
          <button
            @click="$emit('page-change', meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50
                   disabled:opacity-40 disabled:cursor-not-allowed transition-colors text-xs"
          >←</button>
          <button
            v-for="page in visiblePages" :key="page"
            @click="typeof page === 'number' && $emit('page-change', page)"
            :disabled="page === '...'"
            class="px-3 py-1.5 rounded-lg border transition-colors text-xs"
            :class="page === meta.current_page
              ? 'bg-indigo-600 text-white border-indigo-600'
              : 'border-slate-200 text-slate-600 hover:bg-slate-50 disabled:cursor-default'"
          >{{ page }}</button>
          <button
            @click="$emit('page-change', meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50
                   disabled:opacity-40 disabled:cursor-not-allowed transition-colors text-xs"
          >→</button>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  columns: { type: Array,   default: () => [] },
  rows:    { type: Array,   default: () => [] },
  loading: { type: Boolean, default: false },
  meta:    { type: Object,  default: null },
})

defineEmits(['page-change'])

const visiblePages = computed(() => {
  if (!props.meta) return []
  const { current_page: cur, last_page: last } = props.meta
  const pages = []
  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= cur - 1 && i <= cur + 1)) pages.push(i)
    else if (pages[pages.length - 1] !== '...') pages.push('...')
  }
  return pages
})
</script>
