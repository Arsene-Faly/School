<script setup>
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});

const item = props.data.item || {};
const categorie = props.data.categorie || '';

function goBack() {
    window.history.back();
}
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div class="bg-gray-50 shadow-lg w-full mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <!-- Bouton retour -->
                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <!-- Titre -->
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800 capitalize">
                        Détails {{ categorie }}{{ categorie.endsWith('e') ? '' : 's' }}
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Voici les détails de cette {{ categorie }}
                    </p>
                </div>

                <!-- Image de couverture -->
                <div class="mb-8 rounded-lg overflow-hidden shadow-md">
                    <img
                        v-if="item.cover_photo"
                        :src="item.cover_photo"
                        :alt="`Photo de couverture ${item.title || ''}`"
                        class="w-full h-64 object-cover object-center"
                    />
                    <div
                        v-else
                        class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 italic"
                    >
                        Pas d'image de couverture
                    </div>
                </div>

                <!-- Titre et description -->
                <h1 class="text-4xl font-extrabold text-indigo-900 mb-4">
                    {{ item.title || 'Titre non disponible' }}
                </h1>

                <div class="prose prose-indigo max-w-none text-gray-700 whitespace-pre-line mb-8">
                    {{ item.description || 'Aucune description disponible.' }}
                </div>

                <!-- Informations complémentaires -->
                <div class="flex flex-wrap items-center gap-4 text-gray-600 mb-6">
                    <div v-if="item.school">
                        École : <span class="font-semibold">{{ item.school.name }}</span>
                    </div>
                    <div v-if="item.views_count !== undefined">
                        <EyeIcon class="inline w-5 h-5 mr-1" /> {{ item.views_count }} vues
                    </div>
                </div>

            </div>
        </div>
    </UserLayout>
</template>
