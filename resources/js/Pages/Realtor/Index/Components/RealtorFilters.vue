<template>
  <form>
    <div class="mb-4 mt-4 flex flex-wrap gap-4">
      <div class="flex flex-nowrap items-center gap-2">
        <input
          id="deleted"
          v-model="filterForm.deleted"
          type="checkbox"
          class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
        />
        <label for="deleted">{{ t('realtor.deleted') }}</label>
      </div>
      <div>
        <select v-model="filterForm.by" class="input-filter-l w-24">
          <option value="created_at">{{ t('realtor.added') }}</option>
          <option value="price">{{ t('realtor.price') }}</option>
        </select>
        <select v-model="filterForm.order" class="input-filter-r w-32">
          <option
            v-for="option in sortOptions"
            :key="option.value"
            :value="option.value"
          >
            {{ option.label }}
          </option>
        </select>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { watch, computed } from 'vue'
import { route } from 'ziggy-js'
import { debounce } from 'lodash'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({ filters: Object })

const sortLabels = computed(() => ({
  created_at: [
    { label: t('realtor.latest'), value: 'desc' },
    { label: t('realtor.oldest'), value: 'asc' },
  ],
  price: [
    { label: t('realtor.pricey'), value: 'desc' },
    { label: t('realtor.cheapest'), value: 'asc' },
  ],
}))

const sortOptions = computed(() => sortLabels.value[filterForm.by])

const filterForm = useForm({
  deleted: props.filters.deleted ?? false,
  by: props.filters.by ?? 'created_at',
  order: props.filters.order ?? 'desc',
})

const sendRequest = debounce(() => {
  router.get(
    route('realtor.listing.index'),
    {
      deleted: filterForm.deleted,
      by: filterForm.by,
      order: filterForm.order,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    },
  )
}, 1000)

watch(
  [() => filterForm.deleted, () => filterForm.by, () => filterForm.order],
  () => { sendRequest() },
)
</script>
