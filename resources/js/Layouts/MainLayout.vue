<template>
  <AppHeader />

  <main class="container mx-auto p-6" style="background-color: #F8F9FB; min-height: calc(100vh - 80px);">
    <div
      v-if="flashSuccess"
      class="mb-5 flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium shadow-sm"
      style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;"
    >
      <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
      </svg>
      {{ flashSuccess }}
    </div>
    <slot />
  </main>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const page = usePage()
const flashSuccess = computed(() => page.props.flash.success)
</script>