<template>
  <h1 class="text-3xl mb-4">{{ t('notifications.title') }}</h1>

  <section v-if="notifications.data.length" class="text-gray-700 dark:text-gray-400">
    <div
      v-for="notification in notifications.data"
      :key="notification.id"
      class="border-b border-gray-200 dark:border-gray-800 py-4 flex justify-between items-center"
    >
      <div>
        <i18n-t
          v-if="notification.type === 'App\\Notifications\\OfferMade'"
          keypath="notifications.offerMade"
          tag="span"
        >
          <template #price>
            <Price :price="notification.data.amount" />
          </template>
          <template #link>
            <Link
              :href="route('realtor.listing.show', { listing: notification.data.listing_id })"
              class="text-indigo-600 dark:text-indigo-400"
            >{{ t('notifications.listing') }}</Link>
          </template>
        </i18n-t>
      </div>
      <div>
        <Link
          v-if="!notification.read_at"
          :href="route('notification.seen', { notification: notification.id })"
          as="button"
          method="put"
          class="btn-outline text-xs font-medium uppercase"
        >
          {{ t('notifications.markAsRead') }}
        </Link>
      </div>
    </div>
  </section>

  <EmptyState v-else>{{ t('notifications.noNotifications') }}</EmptyState>

  <section v-if="notifications.data.length" class="w-full flex justify-center mt-8 mb-8">
    <Pagination :links="notifications.links" />
  </section>
</template>

<script setup>
import Price from '@/Components/Price.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'
import Pagination from '@/Components/UI/Pagination.vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({ notifications: Object })
</script>
