<script setup>
import { HopIcon, ArrowRightIcon, ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
  school: Object,
});

const programs = props.school?.formations ?? [];
const itemsPerPage = 4;
const currentPage = ref(1);
const totalPages = computed(() => Math.ceil(programs.length / itemsPerPage));
const paginatedPrograms = computed(() =>
  programs.slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage)
);

const goToPage = page => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    document.querySelector('#programsSection')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};
</script>

<template>
  <div id="programsSection" class="bg-white rounded-2xl shadow-xl overflow-hidden mb-12 p-6 md:p-12">
    <!-- Header -->
    <h2 class="text-3xl md:text-4xl font-extrabold text-indigo-900 mb-10 flex items-center gap-3">
      <HopIcon class="w-7 h-7 text-indigo-600" /> Formations disponibles
    </h2>

    <!-- Programmes -->
    <div class="space-y-20 max-w-6xl mx-auto">
      <div v-for="(program, i) in paginatedPrograms" :key="program.id || i" class="grid md:grid-cols-2 gap-12 items-center">

        <!-- Image -->
        <div :class="i % 2 ? 'md:order-2' : ''" class="relative w-full h-80 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-transform duration-500">
          <img :src="program.cover_photo" alt="Image programme" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
        </div>

        <!-- Description -->
        <div :class="i % 2 ? 'md:order-1 text-right md:text-right' : ''">
          <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 tracking-tight">{{ program.title }}</h3>
          <p class="text-gray-700 mb-6 text-base md:text-lg leading-relaxed">{{ program.description }}</p>
          <button class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300">
            Voir les détails <ArrowRightIcon class="w-5 h-5 ml-2"/>
          </button>
        </div>
      </div>

      <!-- Pagination -->
      <nav v-if="totalPages > 1" class="flex justify-center items-center mt-16 space-x-3 select-none">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage===1" class="p-3 rounded-lg bg-gray-800 hover:bg-indigo-600 disabled:opacity-50 transition">
          <ChevronLeftIcon class="w-6 h-6 text-white"/>
        </button>

        <button v-for="page in totalPages" :key="page" @click="goToPage(page)"
          :class="[
            'px-5 py-2 rounded-lg font-medium transition',
            page===currentPage ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'
          ]">
          {{ page }}
        </button>

        <button @click="goToPage(currentPage + 1)" :disabled="currentPage===totalPages" class="p-3 rounded-lg bg-gray-800 hover:bg-indigo-600 disabled:opacity-50 transition">
          <ChevronRightIcon class="w-6 h-6 text-white"/>
        </button>
      </nav>
    </div>
  </div>
</template>
