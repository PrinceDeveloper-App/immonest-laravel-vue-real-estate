<template>
  <!-- ─── Top Header ─────────────────────────────────────────────── -->
  <header class="w-full shadow-md bg-navy">
    <div class="container mx-auto px-6">
      <div class="flex items-center justify-between h-20">
        <!-- Left: Hamburger -->
        <button
          class="flex flex-col items-center gap-1 cursor-pointer group focus:outline-none"
          :aria-label="t('nav.openMenu')"
          @click="openMenu = true"
        >
          <span
            v-for="n in 3"
            :key="n"
            class="block w-6 h-0.5 rounded-full bg-gold transition-all duration-200 group-hover:w-7"
          />
          <span class="text-xs font-semibold tracking-widest mt-1 text-gold">{{ t('nav.menu') }}</span>
        </button>

        <!-- Center: Logo -->
        <Link
          class="absolute left-1/2 -translate-x-1/2 select-none"
          :href="route('listing.index')"
        >
          <span class="text-2xl font-bold tracking-widest text-white uppercase">Immo</span><span
            class="text-2xl font-bold tracking-widest uppercase text-gold"
          >Nest</span>
          <span class="block text-center text-xs tracking-[0.25em] font-light -mt-1 text-gold/65">
            {{ t('nav.realEstate') }}
          </span>
        </Link>

        <!-- Right: Auth + Language Toggle -->
        <div class="flex items-center gap-3">
          <!-- Language Toggle -->
          <div class="flex items-center rounded border border-gold/30 overflow-hidden text-xs font-semibold">
            <button
              class="px-2.5 py-1.5 transition-colors duration-150"
              :class="locale === 'en' ? 'bg-gold text-navy' : 'text-gold/60 hover:text-gold'"
              @click="setLocale('en')"
            >
              EN
            </button>
            <span class="w-px h-4 bg-gold/30" />
            <button
              class="px-2.5 py-1.5 transition-colors duration-150"
              :class="locale === 'de' ? 'bg-gold text-navy' : 'text-gold/60 hover:text-gold'"
              @click="setLocale('de')"
            >
              DE
            </button>
          </div>

          <template v-if="user">
            <Link
              class="relative text-white text-lg leading-none"
              :href="route('notification.index')"
            >
              🔔
              <span
                v-if="notificationCount"
                class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-gold text-navy text-xs flex items-center justify-center font-bold"
              >{{ notificationCount }}</span>
            </Link>
            <Link
              class="hidden sm:inline text-sm font-medium text-white/80 hover:text-white transition-colors"
              :href="route('realtor.listing.index')"
            >
              {{ t('nav.hello', { name: user.name }) }}
            </Link>
            <Link
              class="header-btn-outline"
              :href="route('logout')"
              method="delete"
            >
              {{ t('nav.logout') }}
            </Link>
          </template>
          <template v-else>
            <Link
              class="header-btn-outline"
              :href="route('user-account.create')"
            >
              {{ t('nav.register') }}
            </Link>
            <Link
              class="header-btn-gold"
              :href="route('login')"
            >
              {{ t('nav.login') }}
            </Link>
          </template>
        </div>
      </div>
    </div>
  </header>

  <!-- ─── Overlay ────────────────────────────────────────────────── -->
  <Transition name="fade">
    <div
      v-if="openMenu"
      class="fixed inset-0 bg-black/60 z-40 backdrop-blur-sm"
      @click="openMenu = false"
    />
  </Transition>

  <!-- ─── Slide-out Drawer ───────────────────────────────────────── -->
  <Transition name="slide">
    <aside
      v-if="openMenu"
      class="fixed top-0 left-0 h-full w-72 z-50 flex flex-col shadow-2xl bg-navy"
    >
      <!-- Drawer Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-gold/20">
        <div>
          <p class="text-white font-bold tracking-widest text-lg uppercase">
            Immo<span class="text-gold">Nest</span>
          </p>
          <p class="text-xs tracking-widest mt-0.5 text-gold/60">{{ t('nav.premiumRealEstate') }}</p>
        </div>
        <button
          class="w-8 h-8 flex items-center justify-center rounded-full border border-gold/40
                 transition-all duration-200 hover:bg-gold hover:border-gold group focus:outline-none"
          :aria-label="t('nav.closeMenu')"
          @click="openMenu = false"
        >
          <svg
            class="w-4 h-4 text-white group-hover:text-navy transition-colors"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Nav Links -->
      <nav class="flex flex-col px-4 pt-6 gap-1">
        <p class="text-xs font-semibold tracking-[0.2em] px-3 mb-3 text-gold/50">{{ t('nav.navigation') }}</p>

        <Link
          class="drawer-link"
          :href="route('listing.index')"
          @click="openMenu = false"
        >
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15v-6H9v6H3.75A.75.75 0 013 21V9.75z"
            />
          </svg>
          {{ t('nav.properties') }}
        </Link>

        <Link
          class="drawer-link"
          :href="route('user-account.create')"
          @click="openMenu = false"
        >
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"
            />
          </svg>
          {{ t('nav.register') }}
        </Link>

        <Link
          class="drawer-link"
          :href="route('login')"
          @click="openMenu = false"
        >
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 12H9m0 0l3-3m-3 3l3 3"
            />
          </svg>
          {{ t('nav.signIn') }}
        </Link>
      </nav>

      <!-- Footer -->
      <div class="mt-auto px-6 py-6 border-t border-gold/15">
        <p class="text-xs text-gold/40 tracking-[0.1em]">{{ t('nav.copyright') }}</p>
      </div>
    </aside>
  </Transition>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()

const setLocale = (lang) => {
  locale.value = lang
  localStorage.setItem('locale', lang)
}

const openMenu = ref(false)
const page = usePage()
const user = computed(() => page.props.user)
const notificationCount = computed(
  () => Math.min(page.props.user?.notificationCount ?? 0, 9),
)
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
