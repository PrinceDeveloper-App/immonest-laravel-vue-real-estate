<template>
  <div>
    Hello World!
    <Link href="/hello">Show Page</Link>
  </div>
  <div>
    <p>
      The message is: {{ message }}
    </p>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
const props = defineProps(['message'])

console.log(props.message)
</script>

