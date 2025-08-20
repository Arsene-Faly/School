<script setup>
import UserLayout from '@/layouts/User/UserLayout.vue'
import { ChevronLeftIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  schoolId: Number,
})

const form = useForm({
  school_id: props.schoolId,
  images: [],
})

const previewUrls = ref([])

function handleFiles(event) {
  const files = Array.from(event.target.files)
  form.images = files
  previewUrls.value = files.map(file => URL.createObjectURL(file))
}

function submit() {
  form.post(route('owner.storegallery.school'), {
    preserveScroll: true,
    forceFormData: true, // très important pour envoyer fichiers
    onSuccess: () => {
      form.reset('images')
      previewUrls.value = []
      Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: 'Les images ont été ajoutées avec succès.',
        confirmButtonColor: '#6366f1',
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: 'Une erreur est survenue lors de l\'envoi.',
        confirmButtonColor: '#e3342f',
      })
    }
  })
}

function goBack() {
  window.history.back()
}
</script>

<template>
  <UserLayout>
    <div class="pt-30 sm:pt-32 pb-12 px-0">
      <div
        class="bg-gray-50 shadow-lg rounded-2xl w-full max-w-7xl mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

        <!-- Bouton Retour -->
        <div class="flex justify-end mb-6">
          <button @click="goBack" type="button"
            class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
            <ChevronLeftIcon class="w-5 h-5" />
            <span>Retour</span>
          </button>
        </div>

        <!-- Titre & Description -->
        <div class="text-center mb-10">
          <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
            Gallery de l’établissement
          </h2>
          <p class="mt-2 text-base sm:text-lg text-gray-600">
            Veuillez remplir les champs ci-dessous pour mettre à jour les informations de votre école.
          </p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">Ajouter des images (multiple)</label>
            <input
              type="file"
              multiple
              accept="image/*"
              @change="handleFiles"
              class="mt-1 block w-full"
            />
          </div>

          <!-- Aperçu images -->
          <div v-if="form.images.length > 0" class="grid grid-cols-2 gap-4">
            <div v-for="(file, index) in form.images" :key="index" class="rounded border p-2">
              <img :src="previewUrls[index]" class="w-full h-auto rounded" />
            </div>
          </div>

          <button
            type="submit"
            class="cursor-pointer px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
            :disabled="form.processing"
          >
            Enregistrer les images
          </button>
        </form>
      </div>
    </div>
  </UserLayout>
</template>
