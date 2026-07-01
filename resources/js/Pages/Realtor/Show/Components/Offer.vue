<template>
  <Box>
    <template #header>
      {{ t('realtor.offerNumber', { id: offer.id }) }}
      <span
        v-if="offer.accepted_at"
        class="dark:bg-green-900 dark:text-green-200 bg-green-200 text-green-900 p-1 rounded-md uppercase ml-1"
      >{{ t('realtor.accepted') }}</span>
    </template>
    <section class="flex items-center justify-between">
      <div>
        <Price :price="offer.amount" class="text-xl" />
        <div class="text-gray-500">
          {{ t('realtor.difference') }} <Price :price="difference" />
        </div>
        <div class="text-gray-500 text-sm">
          {{ t('realtor.madeBy', { name: offer.bidder.name }) }}
        </div>
        <div class="text-gray-500 text-sm">
          {{ t('realtor.madeOn', { date: madeOn }) }}
        </div>
      </div>
      <div>
        <Link
          v-if="!isSold"
          :href="route('realtor.offer.accept', { offer: offer.id })"
          class="btn-outline text-xs font-medium"
          method="put"
          as="button"
        >
          {{ t('realtor.accept') }}
        </Link>
      </div>
    </section>
  </Box>
</template>

<script setup>
import Price from '@/Components/Price.vue'
import Box from '@/Components/UI/Box.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  offer: Object,
  listingPrice: Number,
  isSold: Boolean,
})
const difference = computed(() => props.offer.amount - props.listingPrice)
const madeOn = computed(() => new Date(props.offer.created_at).toDateString())
</script>
