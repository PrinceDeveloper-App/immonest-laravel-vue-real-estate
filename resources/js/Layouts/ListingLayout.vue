<template>
  <!-- ─── Hero: Transparent Header + Filter ─────────────────────── -->
  <section class="relative">

    <!-- Background image -->
    <div
      class="absolute inset-0 bg-cover bg-center bg-no-repeat"
      style="background-image: url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');"
    />

    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-navy/90 via-navy/70 to-navy/95" />

    <!-- Transparent header -->
    <AppHeader :transparent="true" />

    <!-- Page title + glass filter -->
    <div class="relative z-10 container mx-auto px-6 pt-6 pb-10">
      <div class="mb-5">
        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-wide">
          {{ t('listing.pageTitle') }}
        </h1>
        <p class="text-white/55 text-sm mt-1.5 font-light tracking-wide">
          {{ t('listing.pageSubtitle') }}
        </p>
      </div>
      <Filters :filters="filters" />
    </div>

  </section>

  <!-- ─── Main content (listing grid) ───────────────────────────── -->
  <main
    class="container mx-auto px-6 py-8"
    style="background-color: #F8F9FB; min-height: 60vh;"
  >
    <div
      v-if="flashSuccess"
      class="mb-5 flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium shadow-sm"
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
    <slot />
  </main>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AppHeader from '@/Components/AppHeader.vue'
import Filters from '@/Pages/Listing/Index/Components/Filters.vue'

const { t } = useI18n()
const page = usePage()

const filters = computed(() => page.props.filters ?? {})
const flashSuccess = computed(() => page.props.flash?.success)
</script>
