<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  MapPinIcon,
  PhoneIcon,
  HeartIcon,
  CalendarIcon,
  UserGroupIcon,
  StarIcon
} from '@heroicons/vue/24/solid'
import { EyeIcon } from '@heroicons/vue/24/outline'
import schoolPhoto from '/public/school.jpg'

// --------------------
// Props
// --------------------
const props = defineProps({
  school: Object,
  average_rating: Number,
  user_rating: Number,
  isFavorited: Boolean
})

// --------------------
// Refs et computed
// --------------------
const rating = ref(props.average_rating)       // moyenne globale
const userRating = ref(props.user_rating)     // note utilisateur
const favorite = ref(props.isFavorited)       // favori
const message = ref('')
const error = ref('')

const schoolInfo = computed(() => props.school?.info || {})
const coverUrl = computed(() => schoolInfo.value.cover_photo || schoolPhoto)
const logoUrl = computed(() => schoolInfo.value.logo || schoolPhoto)
const nombreEtudiants = computed(() => schoolInfo.value.nombre_etudiants ?? 0)
const description = computed(() => schoolInfo.value.description || 'Aucune description fournie pour cette école.')
const dateCreation = computed(() => {
  const d = schoolInfo.value.date_creation
  if (!d) return '—'
  try { return new Date(d).toLocaleDateString() }
  catch { return d }
})

// Affichage étoiles
const stars = computed(() => {
  const fullStars = Math.round(rating.value)
  return Array.from({ length: 5 }, (_, i) => i < fullStars)
})

// --------------------
// Functions
// --------------------

// Contacter le propriétaire
const contactOwner = () => {
  router.visit(route('messages.index', { receiver: props.school.user.id }))
}

// Ajouter / retirer des favoris
const toggleFavorite = async () => {
  try {
    await axios.post(route('schools.favorite', props.school.id))
    favorite.value = !favorite.value
  } catch (err) {
    console.log(err)
  }
}

// Envoyer une note
const submitRating = async () => {
  message.value = ''
  error.value = ''

  if (!userRating.value || userRating.value < 1 || userRating.value > 5) {
    error.value = 'La note doit être comprise entre 1 et 5.'
    return
  }

  try {
    const res = await axios.post(`/ecoles/${props.school.id}/rate`, {
      etoile: userRating.value
    })
    rating.value = parseFloat(res.data.average)
    message.value = res.data.message || 'Note enregistrée'
  } catch (err) {
    error.value = err.response?.data?.message || 'Erreur'
  }
}

</script>

<template>
  <article class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden mb-12">
    <!-- Header -->
    <header class="relative h-64 sm:h-96">
      <img :src="coverUrl" :alt="`Couverture ${props.school?.name}`" class="w-full h-full object-cover brightness-75 transition-transform hover:scale-105 duration-500" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
      <div class="absolute left-6 right-6 bottom-6 flex flex-col sm:flex-row items-end sm:items-center gap-6">
        <div class="flex-none -translate-y-6 sm:-translate-y-10">
          <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-2xl overflow-hidden bg-white/20 p-2 shadow-2xl border-2 border-white/30 transition-transform hover:scale-105">
            <img :src="logoUrl" :alt="`Logo ${props.school?.name}`" class="w-full h-full object-contain" />
          </div>
        </div>
        <div class="flex-1 text-white">
          <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight drop-shadow-lg">{{ props.school?.name }}</h1>
          <div class="mt-3 flex flex-wrap items-center gap-3 text-sm sm:text-base">
            <span :class="['inline-flex items-center px-3 py-1 rounded-full font-semibold text-xs sm:text-sm',
                          props.school?.type === 'public' ? 'bg-green-600/90 text-white' : 'bg-yellow-500/90 text-white']">
              {{ props.school?.type }}
            </span>
            <div class="flex items-center gap-2 text-gray-200/90">
              <MapPinIcon class="w-5 h-5" />
              <span class="truncate max-w-xs sm:max-w-md">{{ props.school?.adresse }}</span>
            </div>
            <div class="flex items-center gap-2 text-gray-200/90">
              <PhoneIcon class="w-5 h-5" />
              <span class="truncate max-w-xs sm:max-w-md">{{ props.school?.contact }}</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Body -->
    <section class="p-6 sm:p-8 space-y-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Note globale -->
        <div class="flex flex-col p-5 bg-gradient-to-br from-yellow-50 to-white rounded-2xl shadow-lg hover:shadow-2xl transition hover:-translate-y-1">
          <span class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Note globale</span>
          <div class="flex items-center gap-3">
            <div class="flex">
              <StarIcon v-for="(filled, i) in stars" :key="i" class="w-6 h-6 transition-colors" :class="filled ? 'text-yellow-400' : 'text-gray-300'" />
            </div>
            <span class="text-lg font-bold text-gray-900">{{ Number(rating).toFixed(1) }}/5</span>
          </div>
        </div>

        <!-- Étudiants -->
        <div class="flex items-center gap-4 p-5 bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-lg hover:shadow-2xl transition hover:-translate-y-1">
          <div class="p-3 bg-indigo-100 rounded-full">
            <UserGroupIcon class="w-6 h-6 text-indigo-600" />
          </div>
          <div>
            <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Étudiants</span>
            <div class="text-lg font-bold text-gray-900">{{ nombreEtudiants }}</div>
          </div>
        </div>

        <!-- Fondée en + vues -->
        <div class="flex justify-between items-center p-5 bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-lg hover:shadow-2xl transition hover:-translate-y-1">
          <div class="flex items-center gap-3">
            <div class="p-3 bg-indigo-100 rounded-full">
              <CalendarIcon class="w-6 h-6 text-indigo-600" />
            </div>
            <div>
              <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fondée en</span>
              <div class="text-lg font-bold text-gray-900">{{ dateCreation }}</div>
            </div>
          </div>
          <div class="flex items-center gap-1 text-indigo-600">
            <EyeIcon class="w-5 h-5" />
            <span class="text-sm font-semibold">{{ props.school.views || 0 }} vues</span>
          </div>
        </div>
      </div>

      <!-- Rating Form -->
      <div class="p-6 bg-white rounded-2xl shadow-lg border border-gray-100 w-full">
        <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Donner une note</h2>
        <form @submit.prevent="submitRating" class="flex flex-wrap items-center gap-4">
          <input v-model.number="userRating" type="number" min="1" max="5" step="1"
            class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:outline-none"
            placeholder="Note (1-5)" />
          <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-300 focus:outline-none transition">
            Envoyer
          </button>
          <p v-if="message" class="mt-3 text-sm font-medium text-green-600 w-full">{{ message }}</p>
          <p v-if="error" class="mt-3 text-sm font-medium text-red-600 w-full">{{ error }}</p>
        </form>
      </div>

      <!-- Actions -->
      <div class="flex flex-wrap gap-4">
        <button @click="contactOwner" type="button"
          class="flex items-center gap-2 px-6 py-2 rounded-2xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 hover:shadow-xl transition">
          <PhoneIcon class="w-5 h-5" /> Contacter
        </button>

        <button @click="toggleFavorite" type="button"
          class="flex items-center gap-2 px-6 py-2 rounded-2xl bg-white border border-gray-200 text-gray-700 font-semibold shadow-md hover:shadow-xl transition">
          <HeartIcon class="w-5 h-5" :class="favorite ? 'text-red-500' : 'text-gray-400'" />
          {{ favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
        </button>
      </div>

      <!-- Description -->
      <div class="mt-6 text-gray-700 leading-relaxed text-justify">
        <p class="line-clamp-4">{{ description }}</p>
      </div>
    </section>
  </article>
</template>

