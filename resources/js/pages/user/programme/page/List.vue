<script setup>
import { ref, watch } from "vue";
import { MapPinIcon, EyeIcon } from "@heroicons/vue/24/solid";
import { ClockIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import TextLink from "@/components/TextLink.vue";

const props = defineProps({
    data: { type: Object, default: () => ({}) }
});

const searchQuery = ref(props.data.search || "");

// 🔍 Envoie la recherche au serveur à chaque modification
watch(searchQuery, (value) => {
    router.visit(
        route('programme.categorie', { categorie: props.data.categorie, search: value }),
        { preserveState: true, preserveScroll: true, replace: true }
    );
});

function goToPage(url) {
    if (url) router.visit(url, { preserveState: true, preserveScroll: true });
}
</script>

<template>
    <section class="rounded-2xl">
        <div class="container mx-auto px-4 mb-16">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="sm:col-span-2 relative">
                        <!-- <label for="search" class="block text-sm font-semibold text-gray-700 mb-3 tracking-wide">
                            🔍 Rechercher
                        </label> -->
                        <div class="relative group">
                            <input id="search" v-model="searchQuery" type="text"
                                placeholder="Rechercher une activité ou école..."
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-2xl bg-white text-gray-900 placeholder-gray-400 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 group-hover:shadow-md" />
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-indigo-400 group-hover:text-indigo-500 transition duration-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste -->
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div v-for="(item, index) in props.data.datas.data" :key="index"
                    class="bg-white rounded-3xl shadow-lg overflow-hidden flex flex-col h-full transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl">
                    <!-- Image -->
                    <div class="relative h-64 overflow-hidden cursor-pointer">
                        <img :src="item.cover_photo" :alt="item.title"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
                    </div>

                    <!-- Contenu -->
                    <div class="flex-1 flex flex-col p-6">
                        <h3
                            class="text-2xl font-bold text-indigo-800 mb-3 hover:text-indigo-700 transition-colors duration-300">
                            {{ item.title || "Titre inconnu" }}
                        </h3>
                        <p class="text-gray-600 flex-grow text-sm leading-relaxed mb-5">
                            {{ item.description || "Pas de description disponible." }}
                        </p>

                        <!-- Infos -->
                        <div class="flex items-center text-gray-500 text-sm space-x-6 mt-auto">
                            <div class="flex items-center space-x-2">
                                <MapPinIcon class="w-5 h-5 text-indigo-500" />
                                <span>{{ item.school?.name || "École inconnue" }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <ClockIcon class="w-5 h-5 text-indigo-500" />
                                <span>{{ item.created_at ? new Date(item.created_at).toLocaleDateString() : "" }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <EyeIcon class="w-5 h-5 text-indigo-500" />
                                <span>{{ item.views_count || 0 }} vues</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <div class="p-6 pt-0">
                        <TextLink :href="route('programme.show', {categorie:props.data.categorie  ,slug: item.slug })"
                            class="block w-full text-center bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold rounded-xl py-2 text-lg shadow-lg transition-all duration-300">
                            Voir détails
                        </TextLink>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center flex-wrap gap-3">
                <button v-for="link in props.data.datas.links" :key="link.label" @click="goToPage(link.url)"
                    :disabled="!link.url"
                    class="min-w-[44px] px-5 py-2 rounded-xl text-sm font-medium shadow-sm transition-all duration-200"
                    :class="[
                        link.active ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100',
                        !link.url && 'cursor-not-allowed text-gray-400 border-gray-300'
                    ]">
                    {{ link.label }}
                </button>
            </div>
        </div>
    </section>
</template>
