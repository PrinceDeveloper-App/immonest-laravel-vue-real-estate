<template>
  <form @submit.prevent="update">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-6">
        <label class="label">{{ t('realtor.titleLabel') }}</label>
        <input v-model="form.title" class="input" type="text" />
        <InputError :message="form.errors.title" />
      </div>
      <div class="col-span-2">
        <label class="label">{{ t('realtor.bedsLabel') }}</label>
        <input v-model.number="form.bedrooms" class="input" type="text" />
        <InputError :message="form.errors.bedrooms" />
      </div>
      <div class="col-span-2">
        <label class="label">{{ t('realtor.bathsLabel') }}</label>
        <input v-model.number="form.bathrooms" class="input" type="text" />
        <InputError :message="form.errors.bathrooms" />
      </div>
      <div class="col-span-2">
        <label class="label">{{ t('realtor.areaLabel') }}</label>
        <input v-model.number="form.area" class="input" type="text" />
        <InputError :message="form.errors.area" />
      </div>
      <div class="col-span-4">
        <label class="label">{{ t('realtor.cityLabel') }}</label>
        <input v-model="form.city" class="input" type="text" />
        <InputError :message="form.errors.city" />
      </div>
      <div class="col-span-2">
        <label class="label">{{ t('realtor.postCodeLabel') }}</label>
        <input v-model="form.code" class="input" type="text" />
        <InputError :message="form.errors.code" />
      </div>
      <div class="col-span-4">
        <label class="label">{{ t('realtor.streetLabel') }}</label>
        <input v-model="form.street" class="input" type="text" />
        <InputError :message="form.errors.street" />
      </div>
      <div class="col-span-2">
        <label class="label">{{ t('realtor.streetNrLabel') }}</label>
        <input v-model.number="form.street_number" class="input" type="text" />
        <InputError :message="form.errors.street_number" />
      </div>
      <div class="col-span-6">
        <label class="label">{{ t('realtor.priceLabel') }}</label>
        <input v-model.number="form.price" class="input" type="text" />
        <InputError :message="form.errors.price" />
      </div>
      <div class="col-span-6">
        <button type="submit" class="btn-primary">{{ t('realtor.updateBtn') }}</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({ listing: Object })

const form = useForm({
  title: props.listing.title,
  bedrooms: props.listing.bedrooms,
  bathrooms: props.listing.bathrooms,
  area: props.listing.area,
  city: props.listing.city,
  code: props.listing.code,
  street: props.listing.street,
  street_number: props.listing.street_number,
  price: props.listing.price,
})
const update = () => form.put(route('realtor.listing.update', { listing: props.listing.id }))
</script>
