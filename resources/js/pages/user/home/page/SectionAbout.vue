<script setup>
import { AcademicCapIcon, GlobeAltIcon, InformationCircleIcon, LightBulbIcon, UserGroupIcon } from '@heroicons/vue/24/solid';

import img from '/public/assets/image/ent.jpg';
import TextLink from '@/components/TextLink.vue';

</script>

<template>
    <div class="mx-auto max-w-7xl rounded-2xl bg-white p-6 px-4 shadow-xl sm:p-8 sm:px-6 md:p-10 lg:px-8">
        <div class="grid items-center gap-10 md:gap-14 lg:grid-cols-2">
            <!-- Image -->
            <figure class="relative mx-auto w-full max-w-md overflow-hidden rounded-xl shadow-lg lg:mx-0 lg:max-w-full">
                <img :src="img" alt="Notre équipe Edu" class="h-auto w-full object-cover transition duration-500" />
                <figcaption class="absolute bottom-2 left-2 rounded-lg bg-gray-800/70 px-3 py-1 text-xs text-white">
                    Notre équipe Edu</figcaption>
            </figure>

            <!-- Texte -->
            <div>
                <h2 class="mb-4 flex items-center gap-3 text-3xl leading-tight font-bold text-gray-800 md:text-4xl">
                    <InformationCircleIcon class="h-8 w-8 text-indigo-600" />
                    Une équipe engagée pour votre avenir éducatif
                </h2>
                <p class="mb-6 text-base text-gray-600 sm:text-lg">
                    Chez <span class="font-semibold text-indigo-600">Edu</span>, nous croyons que chaque étudiant mérite
                    de réussir. Notre mission est
                    de rendre l'éducation plus accessible, plus humaine et plus adaptée aux besoins de chacun. Notre
                    équipe est composée d’experts
                    pédagogiques et de passionnés d’innovation éducative.
                </p>

                <!-- Atouts -->
                <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <span class="rounded-full bg-indigo-100 p-2 text-indigo-600">
                            <GlobeAltIcon class="h-6 w-6" />
                        </span>
                        <span class="text-sm text-gray-700 sm:text-base"> Éducation accessible partout et pour tous
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="rounded-full bg-indigo-100 p-2 text-indigo-600">
                            <UserGroupIcon class="h-6 w-6" />
                        </span>
                        <span class="text-sm text-gray-700 sm:text-base"> Suivi personnalisé selon les besoins </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="rounded-full bg-indigo-100 p-2 text-indigo-600">
                            <LightBulbIcon class="h-6 w-6" />
                        </span>
                        <span class="text-sm text-gray-700 sm:text-base"> Interface claire pour explorer les formations
                        </span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="rounded-full bg-indigo-100 p-2 text-indigo-600">
                            <AcademicCapIcon class="h-6 w-6" />
                        </span>
                        <span class="text-sm text-gray-700 sm:text-base"> Orientation scolaire et professionnelle de
                            qualité </span>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <span
                        class="inline-block rounded-full bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white sm:text-base">
                        Depuis 2023 : des milliers d’étudiants accompagnés
                    </span>
                    <TextLink :href="route('about')"
                        class="cursor-pointer rounded-full border border-indigo-600 px-4 py-2 text-sm font-medium text-indigo-600 transition hover:bg-indigo-50 sm:text-base">
                        En savoir plus
                    </TextLink>
                </div>
            </div>
        </div>
    </div>
</template>
