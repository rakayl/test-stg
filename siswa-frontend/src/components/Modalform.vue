<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:p-4">
        <!-- Backdrop -->
        <div
          class="absolute inset-0 bg-black/50 backdrop-blur-sm"
          @click="$emit('update:modelValue', false)"
        />

        <!-- Dialog -->
        <div
          class="modal-box relative w-full bg-white sm:rounded-2xl shadow-2xl overflow-hidden
                 rounded-t-2xl max-h-[95dvh] sm:max-h-[90vh] flex flex-col"
          :style="{ maxWidth: smUp ? maxWidth : '100%' }"
        >
          <!-- Header -->
          <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 shrink-0">
            <h3 class="font-semibold text-slate-800 text-base">{{ title }}</h3>
            <button
              @click="$emit('update:modelValue', false)"
              class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="px-5 py-5 overflow-y-auto overflow-x-hidden flex-1">
            <slot />
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-slate-100 bg-slate-50/60 shrink-0">
            <button type="button" @click="$emit('update:modelValue', false)" class="btn-secondary">
              Batal
            </button>
            <button type="button" @click="$emit('submit')" :disabled="loading" class="btn-primary">
              <svg v-if="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
              </svg>
              {{ submitLabel }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue:  { type: Boolean, default: false },
  title:       { type: String,  default: 'Form' },
  submitLabel: { type: String,  default: 'Simpan' },
  loading:     { type: Boolean, default: false },
  maxWidth:    { type: String,  default: '520px' },
})
defineEmits(['update:modelValue', 'submit'])

// Deteksi sm breakpoint untuk maxWidth logic
const smUp = computed(() => {
  if (typeof window === 'undefined') return true
  return window.innerWidth >= 640
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity .2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-active .modal-box { transition: transform .25s ease, opacity .25s ease; }
.modal-enter-from .modal-box  { transform: translateY(20px); opacity: 0; }
@media (min-width: 640px) {
  .modal-enter-from .modal-box { transform: scale(.96) translateY(10px); }
}
</style>
