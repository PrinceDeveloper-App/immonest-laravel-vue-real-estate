<template>
  <form @submit.prevent="register">
    <div class="w-1/2 mx-auto mt-10 p-6 border rounded">
      <div>
        <label for="name" class="label">{{ t('auth.nameLabel') }}</label>
        <input id="name" v-model="form.name" type="text" class="input" />
        <div v-if="form.errors.name" class="input-error">{{ form.errors.name }}</div>
      </div>
      <div>
        <label for="email" class="label">{{ t('auth.emailLabel') }}</label>
        <input id="email" v-model="form.email" type="text" class="input" />
        <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
      </div>
      <div class="mt-4">
        <label for="password" class="label">{{ t('auth.passwordLabel') }}</label>
        <input id="password" v-model="form.password" type="password" class="input" />
        <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
      </div>
      <div class="mt-4">
        <label for="password_confirmation" class="label">{{ t('auth.confirmPasswordLabel') }}</label>
        <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="input" />
      </div>
      <div class="mt-4">
        <button class="btn-primary w-full" type="submit">{{ t('auth.registerBtn') }}</button>
        <div class="mt-4 text-center">
          <Link :href="route('login')" class="text-sm text-blue-500">{{ t('auth.hasAccount') }}</Link>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { route } from 'ziggy-js'

const { t } = useI18n()

const form = useForm({
  name: null,
  email: null,
  password: null,
  password_confirmation: null,
})

const register = () => {
  form.post(route('user-account.store'))
}
</script>
