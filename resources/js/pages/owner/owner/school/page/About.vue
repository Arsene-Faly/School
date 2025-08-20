<script setup>
import { CogIcon } from "lucide-vue-next";
import { ref } from "vue";

const props = defineProps({
    school: Object
});

const openIndex = ref(null);

const toggleAccordion = (index) => {
    openIndex.value = openIndex.value === index ? null : index;
};

// Utilise les champs individuels de school.about avec texte par défaut
const details = [
    { title: "Mission", content: props.school.about?.mission || "Aucune mission définie pour le moment." },
    { title: "Vision", content: props.school.about?.vision || "Aucune vision définie pour le moment." },
    { title: "Description", content: props.school.about?.description || "Aucune description disponible pour le moment." },
    { title: "Histoire", content: props.school.about?.history || "Aucune histoire disponible pour le moment." },
];
</script>

<template>
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center">
            <CogIcon class="w-6 h-6 text-indigo-600 mr-2" />
            À propos de l’établissement
        </h2>

        <div class="space-y-4 mb-16">
            <div v-for="(item, index) in details" :key="index"
                class="bg-white rounded-lg shadow hover:shadow-md transition border border-gray-200">
                <button
                    class="w-full text-left px-6 py-4 flex justify-between items-center text-indigo-700 hover:bg-indigo-50 font-semibold transition"
                    @click="toggleAccordion(index)">
                    <span class="text-lg">{{ item.title }}</span>
                    <svg :class="['w-5 h-5 transform transition-transform duration-200', openIndex === index ? 'rotate-180' : 'rotate-0']"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div v-if="openIndex === index"
                    class="px-6 pb-6 text-gray-700 text-sm leading-relaxed pt-3 whitespace-pre-line">
                    {{ item.content }}
                </div>
            </div>
        </div>
    </div>
</template>
