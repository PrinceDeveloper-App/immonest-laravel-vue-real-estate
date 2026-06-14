<template>
  <header class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 w-full">
    <div class="container mx-auto">
      <nav class="p-4 flex items-center justify-between">
        <div class="text-lg font-medium">
          <Link :href="route('listing.index')">Listings</Link>
        </div>
        <div class="text-xl text-indigo-600 dark:text-indigo-300 font-bold text-center">
          <Link :href="route('listing.index')">ImmoNest</Link>
        </div>
        <div v-if="user" class="flex items-center space-x-4">
          <Link class="text-sm text-gray-600 dark:text-gray-400" :href="route('realtor.listing.index')">Hello, {{ user.name }}</Link>
          <Link :href="route('realtor.listing.create')" class="btn-primary">+ New Listing</Link>
          <Link :href="route('logout')" class="btn-secondary" method="delete">Logout</Link>
        </div>
        <div v-else class="flex items-center space-x-4">
          <Link :href="route('user-account.create')" class="btn-primary">Register</Link>
          <Link :href="route('login')" class="btn-primary">Login</Link>
        </div>
      </nav>
    </div>
  </header>
  
  <main class="container mx-auto p-4">
    <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
      {{ flashSuccess }}
    </div>
    <slot>Default</slot>
  </main>
</template>

<script setup>
import {computed} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import { route } from 'ziggy-js'
// page.props.value.flash.success
const page = usePage()
const flashSuccess = computed(() => page.props.flash.success)
const user = computed(() => page.props.user)
</script>

<style scoped>
.success {
  background-color: #d4edda;
  color: #155724;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}   
</style>