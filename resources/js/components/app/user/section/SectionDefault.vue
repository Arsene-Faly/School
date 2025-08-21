<script setup>
import img from '/public/assets/image/fond/disposition-des-elements-de-travail-vue-de-dessus.jpg';

defineProps({
  title: String,
  description: String
});

import schoolPhoto from '/public/school.jpg'
</script>

<template>
  <section
    class="relative flex items-center justify-center overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-900 to-purple-900 pt-32"
  >
    <!-- Image de fond avec opacité -->
    <div class="absolute inset-0">
      <img
        :src="img"
        alt="Background"
        class="h-full w-full object-cover opacity-10"
        draggable="false"
      />
    </div>

    <!-- Contenu centré -->
    <div class="relative z-10 w-full px-1 sm:px-4 py-24 sm:py-32 md:py-40 text-white text-center">
      <div class="max-w-4xl mx-auto">
        <h1 id="title"
          class="mb-6 text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight font"
        >
          {{ title }}
        </h1>
        <p
          class="mx-auto max-w-3xl text-lg sm:text-xl md:text-2xl opacity-90"
        >
          {{ description }}
        </p>
      </div>
    </div>
  </section>
</template>
