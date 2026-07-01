<template>
  <div class="w-full max-w-4xl mx-auto text-center px-4">
    <!-- Badge -->
    <div class="inline-flex items-center gap-2 px-5 py-1.5 rounded-full border border-gold/50 text-gold text-xs tracking-[0.2em] font-semibold mb-8 uppercase">
      <span class="w-1 h-1 rounded-full bg-gold inline-block" />
      {{ t('home.badge') }}
      <span class="w-1 h-1 rounded-full bg-gold inline-block" />
    </div>

    <!-- Main Title -->
    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight tracking-tight text-white mb-5">
      {{ t('home.titleLine1') }}
      <span class="block text-gold">{{ t('home.titleLine2') }}</span>
    </h1>

    <!-- Subtitle -->
    <p class="text-white/65 text-lg md:text-xl font-light tracking-wide max-w-2xl mx-auto mb-12">
      {{ t('home.subtitle') }}
    </p>

    <!-- Glass Filter Card -->
    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 max-w-2xl mx-auto shadow-2xl">
      <div class="flex flex-col sm:flex-row gap-3">
        <input
          v-model.number="priceFrom"
          type="number"
          :placeholder="t('home.priceFrom')"
          class="flex-1 w-full bg-white/15 border border-gold/40 text-white placeholder-white/40
                 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold focus:bg-white/20
                 transition-colors duration-200"
        />
        <input
          v-model.number="priceTo"
          type="number"
          :placeholder="t('home.priceTo')"
          class="flex-1 w-full bg-white/15 border border-gold/40 text-white placeholder-white/40
                 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold focus:bg-white/20
                 transition-colors duration-200"
        />
        <button
          class="w-full sm:w-auto px-8 py-3 bg-gold text-navy font-semibold text-sm rounded-lg
                 hover:brightness-90 transition-all duration-200 tracking-wide whitespace-nowrap"
          @click="search"
        >
          {{ t('home.searchBtn') }}
        </button>
      </div>
      <p class="text-white/35 text-xs mt-4 tracking-widest uppercase">
        {{ t('home.filterHint') }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'
import HomeLayout from '@/Layouts/HomeLayout.vue'

defineOptions({ layout: HomeLayout })

const { t } = useI18n()

const priceFrom = ref(null)
const priceTo = ref(null)

const search = () => {
  router.get(
    route('listing.index'),
    {
      ...(priceFrom.value && { priceFrom: priceFrom.value }),
      ...(priceTo.value && { priceTo: priceTo.value }),
    },
    { preserveScroll: false },
  )
}
</script>
