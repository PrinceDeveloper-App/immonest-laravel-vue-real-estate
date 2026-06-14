<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-6">
        <label class="label">Title</label>
        <input v-model="form.title" class="input" type="text" />
        <InputError :message="form.errors.title" />
      </div>

      <div class="col-span-2">
        <label class="label">Beds</label>
        <input v-model.number="form.bedrooms" class="input" type="text" />
        <InputError :message="form.errors.bedrooms" />
      </div>

      <div class="col-span-2">
        <label class="label">Baths</label>
        <input v-model.number="form.bathrooms" class="input" type="text" />
        <InputError :message="form.errors.bathrooms" />
      </div>

      <div class="col-span-2">
        <label class="label">Area</label>
        <input v-model.number="form.area" class="input" type="text" />
        <InputError :message="form.errors.area" />
      </div>

      <div class="col-span-4">
        <label class="label">City</label>
        <input v-model="form.city" class="input" type="text" />
        <InputError :message="form.errors.city" />
      </div>

      <div class="col-span-2">
        <label class="label">Post Code</label>
        <input v-model="form.code" class="input" type="text" />
        <InputError :message="form.errors.code" />
      </div>

      <div class="col-span-4">
        <label class="label">Street</label>
        <input v-model="form.street" class="input" type="text" />
        <InputError :message="form.errors.street" />
      </div>

      <div class="col-span-2">
        <label class="label">Street Nr</label>
        <input v-model.number="form.street_number" class="input" type="text" />
        <InputError :message="form.errors.street_number" />
      </div>

      <div class="col-span-6">
        <label class="label">Price</label>
        <input v-model.number="form.price" class="input" type="text" />
        <InputError :message="form.errors.price" />
      </div>

      <div class="col-span-6">
        <button type="submit" class="btn-primary">Create</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
const form = useForm({
  title: null,
  bedrooms: 0,
  bathrooms: 0,
  area: 0,
  city: null,
  code: null,
  street: null,
  street_number: null,
  price: 0,
})
const create = () => form.post(route('realtor.listing.store'))

</script>
