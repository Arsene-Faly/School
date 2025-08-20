<script setup lang="ts">
import { ref, computed, watchEffect } from "vue";
import TextLink from "@/components/TextLink.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({ testimonial: [] }),
  },
});


interface User {
    id: number
    name: string
    email: string
    role?: string | null
}

const page = usePage()
const user = page.props.auth.user as User

const testimonials = computed(() => props.data?.testimonial || []);

const currentIndex = ref(0);

watchEffect(() => {
  console.log("Current Index:", currentIndex.value);
  console.log("Testimonials:", testimonials.value);
});

const nextSlide = () => {
  if (testimonials.value.length === 0) return;
  currentIndex.value = (currentIndex.value + 1) % testimonials.value.length;
};

const prevSlide = () => {
  if (testimonials.value.length === 0) return;
  currentIndex.value =
    (currentIndex.value - 1 + testimonials.value.length) % testimonials.value.length;
};
</script>

<template>
  <div class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-0 overflow-hidden">
    <div
      class="flex transition-transform duration-700 ease-in-out"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <div
        v-for="(testimonial, index) in testimonials"
        :key="index"
        class="min-w-full px-2 md:px-6"
      >
        <div
          class="flex flex-col items-center gap-6 rounded-3xl bg-white border border-gray-200 p-6 sm:p-10 shadow-lg"
        >
          <!-- Quote Icon -->
          <svg
            class="w-10 h-10 text-indigo-300"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M9 12h.01M15 12h.01M6.5 17h11a2.5 2.5 0 002.5-2.5v-7A2.5 2.5 0 0017.5 5h-11A2.5 2.5 0 004 7.5v7A2.5 2.5 0 006.5 17z"
            />
          </svg>

          <!-- Témoignage -->
          <p class="text-center text-lg sm:text-xl md:text-2xl font-light text-gray-700 leading-relaxed">
            "{{ testimonial.description }}"
          </p>

          <!-- Nom et rôle -->
          <div class="text-center mt-4">
            <p class="text-gray-900 font-semibold text-base sm:text-lg">
              {{ testimonial.user.name }}
            </p>
            <!-- <p class="text-indigo-500 text-sm font-medium">{{ testimonial.role }}</p> -->
          </div>
        </div>
      </div>
    </div>

    <!-- Boutons navigation -->
    <button
      @click="prevSlide"
      aria-label="Précédent"
      class="absolute top-1/2 left-3 sm:left-4 -translate-y-1/2 z-10 bg-white border border-gray-300 shadow-md hover:bg-indigo-50 text-indigo-600 rounded-full p-2 sm:p-3 transition"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <button
      @click="nextSlide"
      aria-label="Suivant"
      class="absolute top-1/2 right-3 sm:right-4 -translate-y-1/2 z-10 bg-white border border-gray-300 shadow-md hover:bg-indigo-50 text-indigo-600 rounded-full p-2 sm:p-3 transition"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>

    <!-- Ajouter un témoignage -->
    <div class="mt-10 flex justify-center">
      <TextLink v-if="user"
        :href="route('about') + '#testimonials'"
        class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-base sm:text-lg font-semibold text-white shadow-md hover:bg-indigo-700 transition-colors duration-300"
      >
        Ajouter un témoignage
      </TextLink>
      <TextLink v-else
        :href="route('login') + '#testimonials'"
        class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-base sm:text-lg font-semibold text-white shadow-md hover:bg-indigo-700 transition-colors duration-300"
      >
        Ajouter un témoignage
      </TextLink>
    </div>
  </div>
</template>

