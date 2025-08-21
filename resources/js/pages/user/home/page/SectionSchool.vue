<script setup>
import 'swiper/css';
import 'swiper/css/autoplay';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

import { BuildingLibraryIcon, CalendarDaysIcon, MapPinIcon, StarIcon, UserGroupIcon } from '@heroicons/vue/24/solid';
import TextLink from '@/components/TextLink.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});

const ecoles = props.data?.schools ?? [];

function getRating(ecole) {
    return ecole?.info?.rating ?? 0;
}

function keyFor(ecole, index) {
    return ecole?.id ?? index;
}

import schoolPhoto from '/public/school.jpg'
</script>

<template>
    <div class="px-4" id="card">
        <Swiper :modules="[Autoplay, Navigation, Pagination]" :slides-per-view="3" :space-between="20" :breakpoints="{
            300: { slidesPerView: 1 },
            640: { slidesPerView: 1 },
            1024: { slidesPerView: 2 },
            1280: { slidesPerView: 3 },
        }" :autoplay="{ delay: 10000, disableOnInteraction: false }" :pagination="{ clickable: true }" navigation
            class="mySwiper">
            <template v-if="ecoles.length">
                <SwiperSlide v-for="(ecole, index) in ecoles" :key="keyFor(ecole, index)" class="h-full">
                    <div
                        class="flex flex-col h-full max-w-full rounded-3xl border border-gray-200 bg-white shadow-md transition-shadow duration-300 hover:shadow-xl dark:border-gray-700 dark:bg-gray-800">
                        <img class="h-72 w-full rounded-t-3xl object-cover"
                            :src="ecole.info?.cover_photo || schoolPhoto"
                            :alt="`Photo de ${ecole.name || 'l\'école'}`" loading="lazy" />
                        <div class="p-8">
                            <div class="mb-6 flex items-center justify-between">
                                <h5
                                    class="cursor-pointer text-2xl font-bold tracking-tight text-gray-800 hover:text-indigo-700 dark:text-white">
                                    {{ ecole.name || 'Nom non disponible' }}
                                </h5>
                                <div class="flex items-center rounded-full bg-gradient-to-r from-indigo-600 to-blue-500 px-4 py-1.5 text-base font-semibold text-white shadow-md"
                                    title="Note">
                                    <StarIcon class="mr-2 h-5 w-5" />
                                    <span>{{ Number(ecole.average_rating).toFixed(1) ?? 0 }}/5</span>
                                </div>
                            </div>

                            <div class="mb-4 flex items-center text-indigo-600">
                                <MapPinIcon class="mr-2 h-6 w-6" />
                                <p class="text-lg font-medium">{{ ecole.adresse || 'Adresse non disponible' }}</p>
                            </div>

                            <p class="mb-6 leading-relaxed text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ ecole.info?.description || 'Description non disponible.' }}
                            </p>

                            <div class="mb-6 grid grid-cols-2 gap-x-6 gap-y-4 text-indigo-700 dark:text-indigo-300">
                                <div class="flex items-center space-x-2">
                                    <CalendarDaysIcon class="h-5 w-5" />
                                    <span class="text-sm">Créée en {{ ecole.info?.date_creation || 'N/A' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <UserGroupIcon class="h-5 w-5" />
                                    <span class="text-sm">{{ ecole.info?.nombre_etudiants?.toLocaleString() ?? 'N/A' }}
                                        étudiants</span>
                                </div>
                                <div class="flex items-center">
                                    <EyeIcon class="w-5 h-5 mr-1" />
                                    {{ ecole.views || 0 }} view
                                </div>
                            </div>

                            <div class="mb-6 flex space-x-1 justify-end">
                                <template v-for="i in 5" :key="i">
                                    <StarIcon class="h-6 w-6"
                                        :class="i <= Math.round(ecole.average_rating || 0) ? 'text-yellow-400' : 'text-indigo-200'" />
                                </template>
                            </div>

                            <TextLink :href="route('etablissement.show', { slug: ecole.slug })"
                                class="inline-block rounded-xl bg-indigo-600 px-8 py-3 text-lg font-semibold text-white transition-colors duration-300 hover:bg-indigo-700">
                                Voir détails
                            </TextLink>

                        </div>
                    </div>
                </SwiperSlide>
            </template>

            <template v-else>
                <div class="text-center p-10 text-gray-500 dark:text-gray-400">
                    Aucun établissement trouvé.
                </div>
            </template>
        </Swiper>

        <div class="mx-auto w-full flex justify-center mt-10">
            <TextLink :href="route('etablissement.categorie', { categorie: 'scolaire' })"
                class="inline-block rounded-xl bg-indigo-600 px-8 py-3 text-lg font-semibold text-white transition-colors duration-300 hover:bg-indigo-700">
                Voir Tous
            </TextLink>
        </div>
    </div>
</template>
