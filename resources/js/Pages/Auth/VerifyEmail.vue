<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

</script>

<template>
  <div>
    You need to verify to see this page!
  </div>
  <div>
    <Link 
      :href="route('verification.send')" 
      method="post" as="button" class="text-indigo-600 dark:text-indigo-400"
    >
      Resend verification Mail
    </Link>
  </div>
</template>