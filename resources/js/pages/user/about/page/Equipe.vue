<script setup>
import { ref } from 'vue'
import face from '/public/assets/image/face/face.png'
import face1 from '/public/assets/image/face/face2.png'
import face2 from '/public/assets/image/face/face3.jpg'

const equipes = ref([
  {
    img: face,
    name: 'RAVOLAHAVANA Graciah Manitra',
    techno: 'Développeur principal',
    service: 'Expert en développement backend et en sécurité des applications.'
  },
  {
    img: face1,
    name: 'RAVOLAHAVANA Graciah Manitra',
    techno: 'Développeur principal',
    service: 'Expert en développement backend et en sécurité des applications.'
  },
  {
    img: face2,
    name: 'RAVOLAHAVANA Graciah Manitra',
    techno: 'Développeur principal',
    service: 'Expert en développement backend et en sécurité des applications.'
  }
])
</script>

<template>
  <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 mt-28">
    <div class="group" v-for="equipe in equipes" :key="equipe.name">
      <div class="relative mb-4 overflow-hidden rounded-xl">
        <img
          :src="equipe.img"
          :alt="equipe.techno"
          class="w-full aspect-[3/4] object-cover object-center transform group-hover:scale-105 transition duration-300 ease-in-out"
        />
        <div
          class="absolute inset-0 flex items-end justify-center p-6 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-black/70 to-transparent group-hover:opacity-100"
        >
          <div class="flex space-x-4">
            <!-- Facebook -->
            <a href="#" class="p-2 bg-white rounded-full hover:bg-indigo-500 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 text-indigo-500 hover:text-white" viewBox="0 0 24 24">
                <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2v-2.9h2V9.3c0-2 1.2-3.1 3-3.1.9 0 1.8.2 1.8.2v2h-1c-1 0-1.3.6-1.3 1.3v1.5h2.2l-.4 2.9h-1.8v7A10 10 0 0022 12z"/>
              </svg>
            </a>
            <!-- Instagram -->
            <a href="#" class="p-2 bg-white rounded-full hover:bg-pink-600 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 text-pink-600 hover:text-white" viewBox="0 0 24 24">
                <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5zm4.25 3.25a5.25 5.25 0 110 10.5 5.25 5.25 0 010-10.5zm0 1.5a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5zm4.38-.88a.88.88 0 110 1.75.88.88 0 010-1.75z"/>
              </svg>
            </a>
            <!-- Email -->
            <a href="mailto:contact@example.com" class="p-2 bg-white rounded-full hover:bg-indigo-500 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0v-2m0 2v2m0-2h8M4 4h16v16H4V4z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
      <div class="text-center">
        <h3 class="text-xl font-bold text-gray-800">{{ equipe.name }}</h3>
        <p class="font-medium text-indigo-600">{{ equipe.techno }}</p>
        <p class="mt-2 text-gray-800">{{ equipe.service }}</p>
      </div>
    </div>
  </div>
</template>
