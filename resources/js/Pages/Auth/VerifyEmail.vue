<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <div>
    {{ t('auth.verifyMessage') }}
  </div>
  <div>
    <Link
      :href="route('verification.send')"
      method="post"
      as="button"
      class="text-indigo-600 dark:text-indigo-400"
    >
      {{ t('auth.resendVerification') }}
    </Link>
  </div>
</template>
