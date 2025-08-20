<script setup>
import { ref, watch } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import { CameraIcon, PlusIcon, MinusIcon } from '@heroicons/vue/24/solid'
import { TrashIcon } from '@heroicons/vue/24/outline'
import TextLink from '@/components/TextLink.vue'

const props = defineProps({
    gallery: {
        type: Array,
        default: () => [],
    },
})

const page = usePage()
const roles = page.props.auth.user.role

const showAll = ref(false)
const modalImage = ref(null)

const localGallery = ref([...props.gallery])
watch(() => props.gallery, (newVal) => {
    localGallery.value = [...newVal]
})

const toggleShowAll = () => {
    showAll.value = !showAll.value
}

const openModal = (image) => {
    modalImage.value = image
}

const closeModal = () => {
    modalImage.value = null
}

const form = useForm()

const deleteImage = (image) => {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('images.destroy', image.id), {
                preserveScroll: true,
                onSuccess: () => {
                    localGallery.value = localGallery.value.filter(img => img.id !== image.id)
                    if (modalImage.value && modalImage.value.id === image.id) {
                        closeModal()
                    }
                    Swal.fire('Supprimé!', 'La photo a été supprimée.', 'success')
                },
                onError: () => {
                    Swal.fire('Erreur!', 'Erreur lors de la suppression.', 'error')
                },
            })
        }
    })
}
</script>

<template>
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center">
            <CameraIcon class="w-6 h-6 text-indigo-600 mr-2" />
            Galerie
        </h2>

        <div v-if="props.gallery == ''"
            class="flex flex-col items-center justify-center py-16 px-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-400 mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 16h4m-2 4h.01M12 20h9M3 4h18M4 8h16M4 16h8" />
            </svg>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Aucun image trouvé</h1>

            <p class="text-gray-600 text-center max-w-sm">
                Soyez le premier à partager votre expérience et à inspirer d’autres personnes.
            </p>

            <div class="mt-10 flex justify-center" v-if="roles == 'owner'">
                <TextLink :href="route('owner.storegallery.school')"
                    class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-base sm:text-lg font-semibold text-white shadow-md hover:bg-indigo-700 transition-colors duration-300">
                    Ajouter des photo
                </TextLink>
            </div>
        </div>

        <div v-else>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div v-for="image in (showAll ? localGallery : localGallery.slice(0, 8))" :key="image.id"
                    class="aspect-[4/3] bg-gray-100 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition cursor-pointer transform hover:scale-105 duration-300 relative"
                    @click="openModal(image)">
                    <img class="w-full h-full object-cover" :src="`/${image.image_path}`"
                        :alt="image.alt || 'Image galerie'" />

                    <button v-if="roles === 'owner'" @click.stop.prevent="deleteImage(image)"
                        class="absolute top-2 right-2 bg-red-600 bg-opacity-90 hover:bg-opacity-100 text-white p-2 rounded-full shadow-lg transition transform hover:scale-110"
                        title="Supprimer la photo" aria-label="Supprimer la photo">
                        <TrashIcon class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <div v-if="localGallery.length > 8" class="mt-6 text-center">
                <button @click="toggleShowAll"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    <component :is="showAll ? MinusIcon : PlusIcon" class="w-5 h-5 mr-2" />
                    {{ showAll ? 'Réduire' : 'Voir plus' }}
                </button>
            </div>

            <div v-if="modalImage"
                class="fixed inset-0 bg-transparent bg-opacity-75 backdrop-blur-[1px] flex items-center justify-center z-[9999]"
                @click.self="closeModal">
                <div class="relative max-w-4xl max-h-full p-4">
                    <button @click="closeModal"
                        class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-gray-300"
                        aria-label="Fermer">
                        &times;
                    </button>
                    <img :src="`/${modalImage.image_path}`" :alt="modalImage.alt || 'Image agrandie'"
                        class="max-w-full max-h-[80vh] rounded-lg shadow-lg" />
                    <p v-if="modalImage.alt" class="text-white mt-2 text-center">{{ modalImage.alt }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
