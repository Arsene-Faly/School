<script setup>
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    formation: Object,
});

function goBack() {
    window.history.back();
}

</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div class="bg-gray-50 shadow-lg w-full mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
                        Détails formations
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Voici les details formations pour votre école
                    </p>
                </div>

                <div class="mb-8 rounded-lg overflow-hidden shadow-md">
                    <img v-if="formation.cover_photo" :src="formation.cover_photo" alt="Photo de couverture"
                        class="w-full h-64 object-cover object-center" />
                    <div v-else class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 italic">
                        Pas d'image de couverture
                    </div>
                </div>

                <h1 class="text-4xl font-extrabold text-indigo-900 mb-4">{{ formation.title }}</h1>

                <div class="prose prose-indigo max-w-none text-gray-700 whitespace-pre-line mb-8">
                    {{ formation.description || "Aucune description disponible." }}
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<style>
/* Utilise la classe 'prose' de Tailwind Typography pour un rendu plus joli des paragraphes */
</style>
