<template>
  <!-- ─── Full-screen Hero Section ──────────────────────────────── -->
  <section class="relative min-h-screen flex flex-col">

    <!-- Background image -->
    <div
      class="absolute inset-0 bg-cover bg-center bg-no-repeat"
      style="background-image: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');"
    />

    <!-- Gradient overlay: dark at top for header, lighter in middle, dark at bottom -->
    <div class="absolute inset-0 bg-gradient-to-b from-navy/85 via-navy/45 to-navy/90" />

    <!-- Transparent header sits in normal flow at the top -->
    <AppHeader :transparent="true" />

    <!-- Hero content (rendered from page slot) -->
    <div class="relative z-10 flex-1 flex items-center justify-center px-4 pb-16">
      <slot />
    </div>

  </section>

  <!-- ─── Flash message (below hero) ───────────────────────────── -->
  <div
    v-if="flashSuccess"
    class="container mx-auto px-6 pt-6"
  >
    <div class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium shadow-sm"
         style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;"
    >
      <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
          clip-rule="evenodd"
        />
      </svg>
      {{ flashSuccess }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const page = usePage()
const flashSuccess = computed(() => page.props.flash?.success)
</script>
