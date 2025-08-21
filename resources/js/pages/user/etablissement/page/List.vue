<script setup>
import { ref, watch, computed, onBeforeUnmount } from "vue";
import { MapPinIcon, EyeIcon } from "@heroicons/vue/24/solid";
import { StarIcon } from "lucide-vue-next";
import TextLink from "@/components/TextLink.vue";
import { router } from "@inertiajs/vue3";
import schoolPhoto from '/public/school.jpg'

// Props
const props = defineProps({
    data: { type: Object, default: () => ({}) }
});

// States
const schools = ref(props.data.schools?.data ?? []);
const paginationLinks = ref(props.data.schools?.links ?? []);
const searchQuery = ref(props.data.search ?? "");
const typeFilter = ref(props.data.type ?? "");

// Compteur de résultats
const resultCount = computed(() => props.data.schools?.total ?? schools.value.length);

// Timeout pour debounce
let searchTimeout = null;

// Fonction pour filtrer recherche + type
function filterSchools() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route("etablissement.categorie", { categorie: props.data.categorie }),
            { search: searchQuery.value, type: typeFilter.value },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    }, 300);
}

// Watch recherche et type
watch(searchQuery, filterSchools);
watch(typeFilter, filterSchools);

// Pagination
function goToPage(url) {
    if (url) router.visit(url, { preserveScroll: true });
}

// Mise à jour lors du changement de props
watch(
    () => props.data,
    ({ schools: s, search, type }) => {
        schools.value = s?.data ?? [];
        paginationLinks.value = s?.links ?? [];
        searchQuery.value = search ?? "";
        typeFilter.value = type ?? "";
    }
);

// Nettoyage du timeout si le composant est démonté
onBeforeUnmount(() => clearTimeout(searchTimeout));
</script>

<template>
<main class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-12 rounded-2xl">

    <!-- Barre de recherche + filtre type -->
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 mb-8 flex flex-col sm:flex-row gap-4 items-center">
        <!-- Recherche -->
        <div class="flex-1 relative">
            <input
                id="search"
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher une activité ou école..."
                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl bg-white text-gray-900 placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300"
            />
            <svg xmlns="http://www.w3.org/2000/svg"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-indigo-400"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
            </svg>
        </div>

        <!-- Filtre type -->
        <div>
            <select v-model="typeFilter"
                class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-2xl bg-white text-gray-900 placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300"
            >
                <option value="">Tous</option>
                <option value="public">Public</option>
                <option value="prive">Privé</option>
            </select>
        </div>
    </div>

    <!-- Liste des écoles -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div v-for="school in schools" :key="school.id"
            class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-transform duration-300 hover:-translate-y-1">
            <div class="flex flex-col md:flex-row">
                <!-- Image -->
                <div class="w-full h-64 md:w-64 md:h-auto flex-shrink-0">
                    <img :src="school.info?.cover_photo || schoolPhoto" :alt="school.name || 'Image école'"
                        class="object-cover w-full h-full rounded-t-3xl md:rounded-l-3xl md:rounded-tr-none" />
                </div>

                <!-- Infos -->
                <div class="p-8 flex flex-col justify-between flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <span
                                class="inline-block bg-indigo-50 text-indigo-700 text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full">
                                {{ school.type || 'Type inconnu' }}
                            </span>
                            <h2
                                class="cursor-pointer text-2xl font-extrabold tracking-tight text-gray-900 hover:text-indigo-600 mt-2 mb-2">
                                {{ school.name || 'Nom inconnu' }}
                            </h2>
                            <div class="flex items-center text-indigo-600 mt-1">
                                <MapPinIcon class="mr-2 h-5 w-5" />
                                <span class="text-lg font-medium">
                                    {{ school.adresse || 'Adresse non disponible' }}
                                </span>
                            </div>
                        </div>

                        <!-- Étoiles -->
                        <div class="mb-6 flex space-x-1">
                            <StarIcon v-for="i in 5" :key="i" class="h-5 w-5"
                                :class="i <= Math.round(school.average_rating || 0) ? 'text-yellow-400' : 'text-indigo-200'" />
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 mt-4 line-clamp-2 text-base">
                        {{ school.info?.description || 'Description non disponible.' }}
                    </p>

                    <!-- Footer -->
                    <div class="mt-6 flex justify-between items-center text-sm text-gray-500">
                        <div class="flex items-center">
                            <EyeIcon class="w-5 h-5 mr-1" /> {{ school.views || 0 }} vues
                        </div>
                        <TextLink :href="route('etablissement.show', { slug: school.slug })"
                            class="inline-block rounded-2xl bg-indigo-600 px-5 py-2 text-lg font-semibold text-white shadow-md transition-all duration-300 hover:bg-indigo-700 hover:shadow-lg">
                            Voir détails
                        </TextLink>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center flex-wrap gap-3">
        <button v-for="link in paginationLinks" :key="link.label" v-html="link.label" @click="goToPage(link.url)"
            :disabled="!link.url"
            class="cursor-pointer min-w-[44px] rounded-xl border px-5 py-2 text-sm font-medium shadow-sm transition-all duration-200"
            :class="[
                link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100',
                !link.url && 'cursor-not-allowed text-gray-400 border-gray-300'
            ]" />
    </div>
</main>
</template>
