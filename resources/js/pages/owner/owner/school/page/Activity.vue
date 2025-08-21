<script setup>
import { HopIcon, ArrowRightIcon, ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps({
    school: {
        type: Object,
        default: () => null,
    },
});

const programs = props.school?.activity ?? [];

const itemsPerPage = 5;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(programs.length / itemsPerPage));

const paginatedPrograms = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return programs.slice(start, start + itemsPerPage);
});

function goToPage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        document.querySelector('#programsSection')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>

<template>
    <div id="programsSection" class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 p-4">
        <div class="relative">
            <h2 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center">
                <HopIcon class="w-6 h-6 text-indigo-600 mr-2" />
                Activité pour cette école
            </h2>
        </div>

        <div v-if="!props.school?.activity || props.school.activity.length === 0"
            class="flex flex-col items-center justify-center py-16 px-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-400 mb-4" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 16h4m-2 4h.01M12 20h9M3 4h18M4 8h16M4 16h8" />
            </svg>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Aucun activity trouvé</h1>

            <p class="text-gray-600 text-center max-w-sm">
                Soyez le premier à partager votre expérience et à inspirer d’autres personnes.
            </p>
        </div>

        <div v-else class="max-w-6xl mx-auto space-y-24">
            <div v-for="(program, index) in paginatedPrograms" :key="program.id || index"
                class="grid md:grid-cols-2 gap-12 items-center group">
                <!-- Image -->
                <div :class="index % 2 === 0 ? '' : 'md:order-2'"
                    class="relative w-full h-72 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition duration-500">
                    <img :src="program.cover_photo" alt="Image programme"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                </div>

                <!-- Infos -->
                <div :class="index % 2 === 0 ? '' : 'md:order-1'">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        {{ program.title }}
                    </h3>
                    <p class="text-gray-700 leading-relaxed mb-6 text-sm md:text-base">
                        {{ program.description }}
                    </p>
                    <button
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition duration-300">
                        Voir les détails
                        <ArrowRightIcon class="w-5 h-5 ml-2" />
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <nav v-if="totalPages > 1" class="flex justify-center items-center mt-16 space-x-3 select-none"
                aria-label="Pagination">
                <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
                    class="p-2 rounded-md bg-gray-800 hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    aria-label="Page précédente">
                    <ChevronLeftIcon class="w-6 h-6 text-white" />
                </button>

                <button v-for="page in totalPages" :key="page" @click="goToPage(page)" :class="[
                    'px-4 py-2 rounded-md font-semibold transition',
                    page === currentPage
                        ? 'bg-indigo-600 text-white shadow-lg'
                        : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white cursor-pointer',
                ]" :aria-current="page === currentPage ? 'page' : null">
                    {{ page }}
                </button>

                <button :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"
                    class="p-2 rounded-md bg-gray-800 hover:bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    aria-label="Page suivante">
                    <ChevronRightIcon class="w-6 h-6 text-white" />
                </button>
            </nav>
        </div>
    </div>
</template>
