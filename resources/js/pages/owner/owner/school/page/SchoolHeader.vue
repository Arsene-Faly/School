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
import { Phone } from 'lucide-vue-next'
import { EyeIcon } from '@heroicons/vue/24/outline'

// ✅ Définition des props
const props = defineProps({
  school: {
    type: Object,
    default: () => ({}),
  },
  average_rating: Number,
  isFavorited: Boolean, // booléen envoyé par Laravel
})

const schoolInfo = computed(() => props.school?.info || {})

const coverUrl = computed(() => schoolInfo.value.cover_photo || '/images/placeholder-cover.jpg')
const logoUrl = computed(() => schoolInfo.value.logo || '/images/placeholder-logo.png')
const nombreEtudiants = computed(() => schoolInfo.value.nombre_etudiants ?? 0)
const description = computed(() => schoolInfo.value.description || 'Aucune description fournie pour cette école.')

const rating = ref(props.average_rating ? props.average_rating / 2 : 0)

const dateCreation = computed(() => {
  const d = schoolInfo.value.date_creation
  if (!d) return '—'
  try {
    return new Date(d).toLocaleDateString()
  } catch {
    return d
  }
})

const userRating = ref(null)
const message = ref('')
const error = ref('')

// ✅ Gestion du rating
const submitRating = async () => {
  message.value = ''
  error.value = ''

  if (!userRating.value || userRating.value < 1 || userRating.value > 5) {
    error.value = 'La note doit être comprise entre 1 et 5.'
    return
  }

  try {
    const response = await axios.post(`/ecoles/${props.school.id}/rate`, {
      etoile: userRating.value,
    })

    message.value = response.data.message || 'Note enregistrée.'
    if (response.data.average !== undefined) {
      rating.value = response.data.average
    }
    userRating.value = null
  } catch (err) {
    error.value = err.response?.data?.message || 'Une erreur est survenue.'
  }
}

function contactOwner() {
  router.visit(route('messages.index', { receiver: props.school.user.id }))
}

const isFavorited = ref(!!props.isFavorited)

// console.log("Favori :", isFavorited)

function toggleFavorite() {
  router.post(route("schools.favorite", props.school.id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      isFavorited.value = !isFavorited.value
    }
  })
}
</script>

<template>
  <article class="mx-auto bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
    <header class="relative h-64 sm:h-96">
      <img
        :src="coverUrl"
        :alt="`Couverture de ${props.school?.name || 'l\'école'}`"
        class="w-full h-full object-cover brightness-75"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>

      <div class="absolute left-6 right-6 bottom-4 flex flex-col sm:flex-row items-end sm:items-center gap-4">
        <div class="flex-none -translate-y-6 sm:-translate-y-10">
          <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-xl overflow-hidden bg-white/10 p-2 shadow-xl border-2 border-white/20">
            <img :src="logoUrl" :alt="`Logo ${props.school?.name || ''}`" class="w-full h-full object-contain" />
          </div>
        </div>

        <div class="flex-1 text-white">
          <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight drop-shadow">
            {{ props.school?.name || 'Nom de l\'école' }}
          </h1>
          <div class="mt-2 flex flex-wrap items-center gap-3 text-sm sm:text-base">
            <span
              :class="[ 'inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold',
                props.school?.type === 'public' ? 'bg-green-600/90 text-white' : 'bg-yellow-500/90 text-white'
              ]"
            >
              {{ props.school?.type || 'Type' }}
            </span>

            <div class="flex items-center gap-2 text-gray-200/90">
              <MapPinIcon class="w-5 h-5" />
              <span class="truncate max-w-xs sm:max-w-md">{{ props.school?.adresse || 'Adresse non renseignée' }}</span>
            </div>

            <div class="flex items-center gap-2 text-gray-200/90">
              <Phone class="w-5 h-5" />
              <span class="truncate max-w-xs sm:max-w-md">{{ props.school?.contact || 'Contact non renseigné' }}</span>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Body -->
    <section class="p-6 sm:p-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="flex-1">
          <!-- Stats -->
          <!-- ... (inchangé) ... -->
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button
            @click="contactOwner"
            type="button"
            class="cursor-pointer inline-flex items-center gap-2 px-5 py-2 rounded-xl border border-indigo-700 bg-indigo-700 text-white hover:bg-indigo-800 transition"
          >
            <PhoneIcon class="w-5 h-5" />
            Contacter
          </button>

          <button
            @click="toggleFavorite"
            type="button"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 hover:shadow-md transition"
          >
            <HeartIcon
              class="w-5 h-5"
              :class="isFavorited ? 'text-red-500' : 'text-gray-400'"
            />
            {{ isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
          </button>
        </div>
      </div>

      <!-- Description -->
      <div class="mt-6 text-gray-700 leading-relaxed">
        <p class="line-clamp-3">{{ description }}</p>
      </div>
    </section>
  </article>
</template>
