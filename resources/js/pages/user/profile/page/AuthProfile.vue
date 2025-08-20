<script setup>
import TextLink from '@/components/TextLink.vue'

const current = route().current()
</script>

<template>
  <div class="flex items-center justify-center p-0 sm:px-4">
    <div class="w-full mx-auto px-6 py-10 bg-gray-50 shadow rounded-3xl space-y-12">
      <nav
        class="flex flex-col md:flex-row md:justify-center md:space-x-8 space-y-4 md:space-y-0 border-b border-gray-200 text-base font-medium overflow-x-auto no-scrollbar"
      >
        <TextLink
          :href="route('profile.user') + '#profile'"
          :class="[
            'pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
            current === 'profile.user'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-400'
          ]"
        >
          Profil
        </TextLink>
        <TextLink
          :href="route('profile.detail') + '#profile'"
          :class="[
            'pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
            current === 'profile.detail'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-400'
          ]"
        >
          Plus de détails
        </TextLink>
        <TextLink
          :href="route('profile.password.edit') + '#profile'"
          :class="[
            'pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
            current === 'profile.password.edit'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-400'
          ]"
        >
          Mot de passe
        </TextLink>
        <TextLink
          :href="route('profile.show') + '#profile'"
          :class="[
            'pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
            current === 'profile.show'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-400'
          ]"
        >
          Voir votre Profil
        </TextLink>
      </nav>

      <!-- Slot for content -->
      <slot />
    </div>
  </div>
</template>
