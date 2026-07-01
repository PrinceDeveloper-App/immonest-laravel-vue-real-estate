<template>
  <div class="flex flex-col-reverse md:grid md:grid-cols-12 gap-4">
    <Box v-if="listing.images.length" class="md:col-span-7 flex items-center">
      <div class="grid grid-cols-2 gap-1">
        <img v-for="image in listing.images" :key="image.id" :src="image.src" />
      </div>
    </Box>
    <EmptyState v-else class="md:col-span-7 flex items-center">{{ t('listing.noImages') }}</EmptyState>
    <div class="md:col-span-5 flex flex-col gap-4">
      <Box>
        <template #header>{{ t('listing.basicInfo') }}</template>
        <div v-if="listing.title" class="text-xl font-bold mb-2">{{ listing.title }}</div>
        <Price :price="listing.price" class="text-2xl font-bold" />
        <ListingSpace :listing="listing" class="text-lg" />
        <ListingAddress :listing="listing" class="text-gray-500" />
      </Box>

      <Box>
        <template #header>{{ t('listing.monthlyPayment') }}</template>
        <div class="text-2xl font-bold">
          <label class="label">{{ t('listing.interestRate', { rate: interestRate }) }}</label>
          <input v-model.number="interestRate" type="range" min="0.1" max="30" step="0.1" class="w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600" />
          <label class="label">{{ t('listing.duration', { years: duration }) }}</label>
          <input v-model.number="duration" type="range" min="3" max="35" step="1" class="w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-600" />
          <div class="text-sm text-gray-500 mt-2">
            <div class="text-sm text-gray-500 mt-2">
              {{ t('listing.yourMonthlyPayment') }}
            </div>
            <Price :price="monthlyPayment" class="text-xl font-bold" />
          </div>
          <div class="text-sm text-gray-500 mt-2">
            <div class="flex justify-between">
              <div>{{ t('listing.totalPaid') }}</div>
              <div><Price :price="totalPaid" class="font-bold" /></div>
            </div>
            <div class="flex justify-between">
              <div>{{ t('listing.principal') }}</div>
              <div><Price :price="props.listing.price" class="font-bold" /></div>
            </div>
            <div class="flex justify-between">
              <div>{{ t('listing.totalInterest') }}</div>
              <div><Price :price="totalInterest" class="font-bold" /></div>
            </div>
          </div>
        </div>
      </Box>

      <MakeOffer
        v-if="user && !offerMade"
        :listing-id="listing.id"
        :price="listing.price"
        @offer-updated="offer = $event"
      />

      <OfferMade v-if="user && offerMade" :offer="offerMade" />
    </div>
  </div>
</template>

<script setup>
import ListingAddress from '@/Components/ListingAddress.vue'
import ListingSpace from '@/Components/ListingSpace.vue'
import Price from '@/Components/Price.vue'
import Box from '../../Components/UI/Box.vue'
import MakeOffer from '@/Pages/Listing/Show/Components/MakeOffer.vue'
import { computed, ref } from 'vue'
import { useMonthlyPayment } from '@/Composables/useMonthlyPayment'
import { usePage } from '@inertiajs/vue3'
import OfferMade from './Show/Components/OfferMade.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const interestRate = ref(2.5)
const duration = ref(25)

const props = defineProps({
  listing: Object,
  offerMade: Object,
})
const offer = ref(props.listing.price)

const { monthlyPayment, totalPaid, totalInterest } = useMonthlyPayment(
  offer, interestRate, duration,
)

const page = usePage()
const user = computed(() => page.props.user)
</script>
