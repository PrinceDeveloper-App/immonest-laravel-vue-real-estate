<template>
  <span class="input-error">{{ props.message }}</span>
</template>

<script setup>
const props = defineProps({
  message: String,
})
</script>