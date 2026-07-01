<template>
  <form @submit.prevent="filter">
    <div class="mb-8 mt-4 flex flex-wrap gap-2">
      <div class="flex flex-nowrap items-center">
        <input
          v-model.number="filterForm.priceFrom"
          type="text"
          :placeholder="t('listing.priceFrom')"
          class="input-filter-l w-28"
        />
        <input
          v-model.number="filterForm.priceTo"
          type="text"
          :placeholder="t('listing.priceTo')"
          class="input-filter-r w-28"
        />
      </div>

      <div class="flex flex-nowrap items-center">
        <select v-model="filterForm.beds" class="input-filter-l w-28">
          <option :value="null">{{ t('listing.beds') }}</option>
          <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
          <option>6+</option>
        </select>
        <select v-model="filterForm.baths" class="input-filter-r w-28">
          <option :value="null">{{ t('listing.baths') }}</option>
          <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
          <option>6+</option>
        </select>
      </div>

      <div class="flex flex-nowrap items-center">
        <input
          v-model.number="filterForm.areaFrom"
          type="text"
          :placeholder="t('listing.areaFrom')"
          class="input-filter-l w-28"
        />
        <input
          v-model.number="filterForm.areaTo"
          type="text"
          :placeholder="t('listing.areaTo')"
          class="input-filter-r w-28"
        />
      </div>

      <button type="submit" class="btn-normal">{{ t('listing.filterBtn') }}</button>
      <button type="reset" @click="clear">{{ t('listing.clearBtn') }}</button>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({ filters: Object })

const filterForm = useForm({
  priceFrom: props.filters.priceFrom ?? null,
  priceTo: props.filters.priceTo ?? null,
  beds: props.filters.beds ?? null,
  baths: props.filters.baths ?? null,
  areaFrom: props.filters.areaFrom ?? null,
  areaTo: props.filters.areaTo ?? null,
})

const filter = () => {
  filterForm.get(
    route('listing.index'),
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}
const clear = () => {
  filterForm.priceFrom = null
  filterForm.priceTo = null
  filterForm.beds = null
  filterForm.baths = null
  filterForm.areaFrom = null
  filterForm.areaTo = null
  filter()
}
</script>
