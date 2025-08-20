<script setup>
import img1 from '/public/assets/image/face/face.png';
import img2 from '/public/assets/image/face/face2.png';
import img3 from '/public/assets/image/face/face3.jpg';

import { EnvelopeIcon, GlobeAltIcon, MapPinIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import { AcademicCapIcon, BookmarkSquareIcon, BuildingOfficeIcon, CalendarDaysIcon } from '@heroicons/vue/24/solid';
</script>

<template>
    <footer class="bg-gradient-to-r from-blue-700 via-indigo-800 to-purple-900 text-white shadow-inner">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-16 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Logo + description -->
            <div>
                <div class="mb-4">
                    <a href="/" class="group flex items-center gap-3">
                        <span class="text-3xl font-bold tracking-wide drop-shadow-sm"> <span class="text-yellow-400 font">Educ</span></span>
                    </a>
                </div>
                <p class="text-base text-indigo-100 md:text-lg">
                    Votre avenir commence ici. Explorez, apprenez et transformez votre futur grâce à nos formations et établissements partenaires.
                </p>
                <div class="mt-6 flex space-x-5">
                    <a href="#" class="transition hover:text-yellow-400">
                        <GlobeAltIcon class="h-5 w-5" />
                    </a>
                    <a href="#" class="transition hover:text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5" viewBox="0 0 24 24">
                            <path
                                d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0022.4.36a9.09 9.09 0 01-2.88 1.1A4.52 4.52 0 0016.11 0C13.2 0 11 2.1 11 4.7c0 .37.04.74.12 1.09A12.88 12.88 0 013 1.1a4.69 4.69 0 00-.61 2.38c0 1.64.87 3.1 2.19 3.95A4.52 4.52 0 012 6.7v.06c0 2.3 1.65 4.23 3.84 4.67A4.56 4.56 0 013 11.4a4.62 4.62 0 004.29 3.2A9.05 9.05 0 012 17.54a12.94 12.94 0 006.29 1.85"
                            />
                        </svg>
                    </a>
                    <a href="#" class="transition hover:text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5" viewBox="0 0 24 24">
                            <path
                                d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 4.41 2.85 8.15 6.84 9.49.5.09.68-.21.68-.47v-1.7c-2.78.61-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.84.09-.65.35-1.09.63-1.34-2.22-.25-4.56-1.11-4.56-4.93 0-1.09.39-1.99 1.03-2.7-.1-.25-.45-1.28.1-2.66 0 0 .84-.27 2.75 1.03A9.56 9.56 0 0112 6.85a9.58 9.58 0 012.5.34c1.91-1.3 2.75-1.03 2.75-1.03.55 1.38.2 2.41.1 2.66.64.71 1.03 1.61 1.03 2.7 0 3.83-2.34 4.67-4.57 4.92.36.31.69.93.69 1.88v2.79c0 .26.18.56.69.47A10 10 0 0022 12c0-5.5-4.46-9.96-10-9.96z"
                            />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Liens utiles -->
            <div>
                <h3 class="mb-4 border-b border-white/20 pb-2 text-2xl font-semibold">Nos Services</h3>
                <ul class="space-y-3 text-base text-indigo-100">
                    <li class="flex items-center gap-2">
                        <AcademicCapIcon class="h-5 w-5 text-yellow-300" />
                        <a href="#" class="transition hover:text-yellow-300">Orientation</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <BuildingOfficeIcon class="h-5 w-5 text-yellow-300" />
                        <a href="#" class="transition hover:text-yellow-300">Établissements</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <BookmarkSquareIcon class="h-5 w-5 text-yellow-300" />
                        <a href="#" class="transition hover:text-yellow-300">Formations</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <CalendarDaysIcon class="h-5 w-5 text-yellow-300" />
                        <a href="#" class="transition hover:text-yellow-300">Rendez-vous</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="mb-4 border-b border-white/20 pb-2 text-xl font-semibold">Contact</h3>
                <ul class="space-y-3 text-base text-indigo-100">
                    <li class="flex items-center gap-2">
                        <MapPinIcon class="h-5 w-5 text-yellow-300" />
                        Antananarivo, Madagascar
                    </li>
                    <li class="flex items-center gap-2">
                        <PhoneIcon class="h-5 w-5 text-yellow-300" />
                        +261 34 54 292 33
                    </li>
                    <li class="flex items-center gap-2">
                        <EnvelopeIcon class="h-5 w-5 text-yellow-300" />
                        arsenefaly1@gmail.com
                    </li>
                </ul>
            </div>

            <!-- Galerie -->
            <div>
                <h3 class="mb-4 border-b border-white/20 pb-2 text-2xl font-semibold">Galerie</h3>
                <div class="grid grid-cols-3 gap-3">
                    <img :src="img1" class="h-20 w-full rounded-lg object-cover transition hover:scale-105 hover:shadow-lg" />
                    <img :src="img2" class="h-20 w-full rounded-lg object-cover transition hover:scale-105 hover:shadow-lg" />
                    <img :src="img3" class="h-20 w-full rounded-lg object-cover transition hover:scale-105 hover:shadow-lg" />
                </div>
            </div>
        </div>

        <!-- Bas de page -->
        <div class="border-t border-white/10 py-6 text-center text-sm text-indigo-200">©2025 Edu by Arsène. Tous droits réservés.</div>
    </footer>
</template>
