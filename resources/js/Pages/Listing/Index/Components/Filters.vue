<template>
  <form @submit.prevent="filter">

    <!-- ── Filter Card ─────────────────────────────────────────── -->
    <div class="filter-card">
      <div class="flex flex-wrap gap-5 items-end">

        <!-- Price Range -->
        <div>
          <span class="filter-label">{{ t('listing.priceRange') }}</span>
          <div class="filter-group">
            <input
              v-model.number="filterForm.priceFrom"
              type="text"
              :placeholder="t('listing.priceFrom')"
              class="filter-input w-32"
            />
            <span class="filter-sep" />
            <input
              v-model.number="filterForm.priceTo"
              type="text"
              :placeholder="t('listing.priceTo')"
              class="filter-input w-32"
            />
          </div>
        </div>

        <!-- Beds & Baths -->
        <div>
          <span class="filter-label">{{ t('listing.roomsLabel') }}</span>
          <div class="filter-group">
            <div class="select-wrap">
              <select v-model="filterForm.beds" class="filter-input w-28">
                <option :value="null">{{ t('listing.beds') }}</option>
                <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                <option>6+</option>
              </select>
              <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <span class="filter-sep" />
            <div class="select-wrap">
              <select v-model="filterForm.baths" class="filter-input w-28">
                <option :value="null">{{ t('listing.baths') }}</option>
                <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                <option>6+</option>
              </select>
              <svg class="chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Area -->
        <div>
          <span class="filter-label">{{ t('listing.areaLabel') }}</span>
          <div class="filter-group">
            <input
              v-model.number="filterForm.areaFrom"
              type="text"
              :placeholder="t('listing.areaFrom')"
              class="filter-input w-28"
            />
            <span class="filter-sep" />
            <input
              v-model.number="filterForm.areaTo"
              type="text"
              :placeholder="t('listing.areaTo')"
              class="filter-input w-28"
            />
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2.5 pb-0.5">
          <button type="submit" class="btn-search">
            {{ t('listing.filterBtn') }}
          </button>
          <button type="button" class="btn-clear" @click="clear">
            {{ t('listing.clearBtn') }}
          </button>
        </div>

      </div>
    </div>

  </form>
</template>

<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({ filters: Object })

const filterForm = useForm({
  priceFrom: props.filters?.priceFrom ?? null,
  priceTo:   props.filters?.priceTo   ?? null,
  beds:      props.filters?.beds      ?? null,
  baths:     props.filters?.baths     ?? null,
  areaFrom:  props.filters?.areaFrom  ?? null,
  areaTo:    props.filters?.areaTo    ?? null,
})

watch(() => props.filters, (f) => {
  filterForm.priceFrom = f?.priceFrom ?? null
  filterForm.priceTo   = f?.priceTo   ?? null
  filterForm.beds      = f?.beds      ?? null
  filterForm.baths     = f?.baths     ?? null
  filterForm.areaFrom  = f?.areaFrom  ?? null
  filterForm.areaTo    = f?.areaTo    ?? null
}, { deep: true })

const filter = () => {
  filterForm.get(route('listing.index'), {
    preserveState: true,
    preserveScroll: true,
  })
}

const clear = () => {
  filterForm.priceFrom = null
  filterForm.priceTo   = null
  filterForm.beds      = null
  filterForm.baths     = null
  filterForm.areaFrom  = null
  filterForm.areaTo    = null
  filter()
}
</script>

<style scoped>
.filter-card {
  border-radius: 1rem;
  padding: 1.25rem 1.5rem;
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  background: rgba(11, 31, 59, 0.78);
  border: 1.5px solid rgba(212, 175, 55, 0.55);
  box-shadow:
    0 8px 40px rgba(0, 0, 0, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

.filter-label {
  display: block;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  margin-bottom: 0.375rem;
  color: rgba(212, 175, 55, 0.80);
}

.filter-group {
  display: flex;
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1.5px solid rgba(212, 175, 55, 0.60);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.20);
}

.filter-input {
  background: #ffffff;
  color: #0B1F3B;
  font-size: 0.8rem;
  font-weight: 500;
  padding: 0.625rem 0.75rem;
  appearance: none;
  min-width: 0;
  border: none;
  outline: none;
  transition: background 0.15s;
}

.filter-input:focus { background: #F8F9FB; }

.filter-input::placeholder { color: rgba(11, 31, 59, 0.38); }

.filter-input > option { background: #ffffff; color: #0B1F3B; }

.filter-sep {
  width: 1px;
  flex-shrink: 0;
  align-self: stretch;
  background: rgba(212, 175, 55, 0.50);
}

.select-wrap { position: relative; display: flex; align-items: center; }

.chevron {
  position: absolute;
  right: 0.5rem;
  width: 0.75rem;
  height: 0.75rem;
  pointer-events: none;
  color: rgba(11, 31, 59, 0.42);
}

.btn-search {
  background: #D4AF37;
  color: #0B1F3B;
  font-size: 0.8rem;
  font-weight: 700;
  padding: 0.625rem 1.75rem;
  border-radius: 0.5rem;
  letter-spacing: 0.06em;
  white-space: nowrap;
  border: none;
  cursor: pointer;
  box-shadow: 0 4px 16px rgba(212, 175, 55, 0.40);
  transition: filter 0.15s, transform 0.1s;
}

.btn-search:hover  { filter: brightness(0.95); }
.btn-search:active { transform: scale(0.98); }

.btn-clear {
  background: transparent;
  color: rgba(212, 175, 55, 0.80);
  font-size: 0.8rem;
  font-weight: 600;
  padding: 0.625rem 1.25rem;
  border-radius: 0.5rem;
  letter-spacing: 0.06em;
  white-space: nowrap;
  cursor: pointer;
  border: 1.5px solid rgba(212, 175, 55, 0.45);
  transition: color 0.2s, border-color 0.2s, background 0.2s;
}

.btn-clear:hover {
  color: #D4AF37;
  border-color: #D4AF37;
  background: rgba(212, 175, 55, 0.08);
}
</style>
