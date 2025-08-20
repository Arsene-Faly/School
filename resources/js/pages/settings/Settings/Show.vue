<script setup>
import AuthProfile from './AuthProfile.vue'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import defaultAvatar from '/public/assets/image/person1.png'

const page = usePage()
const user = page.props.auth.user.info ?? {}
const user1 = page.props.auth.user ?? {}

const avatar = computed(() => user.avatar ? `/${user.avatar}` : defaultAvatar)

const genderLabel = computed(() => {
  if (!user.gender) return ''
  if (user.gender.toLowerCase() === 'male') return 'Homme'
  if (user.gender.toLowerCase() === 'female') return 'Femme'
  return user.gender
})
</script>

<template>
    <AuthProfile>
      <div
        class="max-w-7xl mx-auto bg-gray-50 rounded-3xl shadow-xl p-12 flex flex-col md:flex-row items-center md:items-start gap-12"
      >
        <!-- Photo + badge -->
        <div
          class="relative w-44 h-44 rounded-full overflow-hidden bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 shadow-lg flex items-center justify-center"
        >
          <img
            :src="avatar"
            :alt="user1.name"
            class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-md"
          />
          <div
            v-if="genderLabel"
            class="absolute bottom-2 left-1/2 transform -translate-x-1/2 bg-indigo-700 text-white text-xs font-semibold px-4 py-1 rounded-full uppercase tracking-wider drop-shadow-md select-none"
          >
            {{ genderLabel }}
          </div>
        </div>

        <!-- Infos principales -->
        <div class="flex-1 space-y-6">
          <h1
            class="text-4xl font-extrabold text-indigo-800 tracking-tight leading-tight drop-shadow-sm"
          >
            {{ user1.name }}
          </h1>

          <p
            class="text-gray-600 text-lg leading-relaxed italic border-l-4 border-indigo-300 pl-4 max-w-prose"
          >
            {{ user.bio || "Cette personne n'a pas encore ajouté de biographie." }}
          </p>

          <div
            class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-base font-medium"
          >
            <div class="flex items-center space-x-3 hover:text-indigo-600 transition">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-indigo-500 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3 5h12M9 3v2m6 12h-6a2 2 0 000 4h6a2 2 0 000-4zM12 9v2m0 4v2"
                />
              </svg>
              <span>{{ user.phone || 'Téléphone non renseigné' }}</span>
            </div>

            <div class="flex items-center space-x-3 hover:text-indigo-600 transition">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-indigo-500 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3 10h2l1 7h12l1-7h2"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16 7a4 4 0 01-8 0"
                />
              </svg>
              <span>{{ user.address || 'Adresse non renseignée' }}</span>
            </div>

            <div class="flex items-center space-x-3 hover:text-indigo-600 transition">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-indigo-500 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16 12a4 4 0 01-8 0 4 4 0 018 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 14v7"
                />
              </svg>
              <span>{{ user.birthdate ? new Date(user.birthdate).toLocaleDateString() : 'Date de naissance non renseignée' }}</span>
            </div>

            <div class="flex items-center space-x-3 hover:text-indigo-600 transition">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-indigo-500 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16 8a6 6 0 01-12 0 6 6 0 0112 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 14v7"
                />
              </svg>
              <span>{{ user1.email }}</span>
            </div>
          </div>
        </div>
      </div>
    </AuthProfile>
</template>
