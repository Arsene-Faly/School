<script setup>
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon, AcademicCapIcon, ClockIcon, CurrencyDollarIcon, GlobeAltIcon, TagIcon, ComputerDesktopIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    formation: Object,
});

function goBack() {
    window.history.back();
}

// Conversion JSON -> tableau si nécessaire
const levels = Array.isArray(props.formation.level)
    ? props.formation.level
    : (props.formation.level ? JSON.parse(props.formation.level) : []);

const categories = Array.isArray(props.formation.category)
    ? props.formation.category
    : (props.formation.category ? JSON.parse(props.formation.category) : []);

// Status configuration
const getStatusConfig = (status) => {
    const configs = {
        draft: { label: 'Brouillon', class: 'bg-amber-50 text-amber-700 border border-amber-200' },
        published: { label: 'Publié', class: 'bg-green-50 text-green-700 border border-green-200' },
        archived: { label: 'Archivé', class: 'bg-gray-50 text-gray-700 border border-gray-200' }
    };
    return configs[status] || configs.draft;
};
</script>

<template>
<UserLayout>
    <div class="min-h-screen bg-gray-50">
        <div class="pt-28 pb-16 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto">

                <!-- Navigation et status -->
                <div class="flex items-center justify-between mb-8">
                    <button
                        @click="goBack"
                        class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors"
                    >
                        <ChevronLeftIcon class="w-5 h-5"/>
                        Retour
                    </button>

                    <!-- <span :class="`px-3 py-1 rounded-full text-sm font-medium ${getStatusConfig(formation.status).class}`">
                        {{ getStatusConfig(formation.status).label }}
                    </span> -->
                </div>

                <!-- Carte principale -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

                    <!-- Image de couverture -->
                    <div class="relative h-64 sm:h-80">
                        <img
                            v-if="formation.cover_photo"
                            :src="formation.cover_photo"
                            alt="Formation cover"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center"
                        >
                            <ComputerDesktopIcon class="w-16 h-16 text-gray-400" />
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-8">

                        <!-- Titre et description -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4 leading-tight">
                                {{ formation.title }}
                            </h1>
                            <p class="text-gray-700 text-lg leading-relaxed">
                                {{ formation.description || "Aucune description disponible." }}
                            </p>
                        </div>

                        <!-- Informations principales -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                            <div class="border-l-4 border-indigo-800 pl-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <ClockIcon class="w-5 h-5 text-indigo-800"/>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Durée</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ formation.duration ? formation.duration + ' mois' : 'Non spécifié' }}
                                </p>
                            </div>

                            <div class="border-l-4 border-yellow-500 pl-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <CurrencyDollarIcon class="w-5 h-5 text-yellow-500"/>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Ecolage</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ formation.price ? formation.price + ' AR' : 'Attente' }}
                                </p>
                            </div>

                            <div class="border-l-4 border-indigo-800 pl-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <GlobeAltIcon class="w-5 h-5 text-indigo-800"/>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Langue</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ formation.language || 'Français' }}
                                </p>
                            </div>

                            <div class="border-l-4 border-yellow-600 pl-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <AcademicCapIcon class="w-5 h-5 text-yellow-600"/>
                                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Niveaux</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ levels.length ? levels.length : '0' }} niveau{{ levels.length > 1 ? 'x' : '' }}
                                </p>
                            </div>

                        </div>

                        <!-- Détails supplémentaires -->
                        <div class="space-y-6">

                            <!-- Niveaux détaillés -->
                            <div v-if="levels.length">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <AcademicCapIcon class="w-5 h-5 text-yellow-600"/>
                                    Niveaux requis
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="level in levels" :key="level"
                                          class="px-3 py-1 bg-yellow-50 text-yellow-800 border border-yellow-200 rounded-md text-sm font-medium">
                                        {{ level }}
                                    </span>
                                </div>
                            </div>

                            <!-- Catégories -->
                            <div v-if="categories.length">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                    <TagIcon class="w-5 h-5 text-indigo-800"/>
                                    Catégories
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="category in categories" :key="category"
                                          class="px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-md text-sm font-medium">
                                        {{ category }}
                                    </span>
                                </div>
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="bg-indigo-800 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-900 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-800 focus:ring-offset-2">
                                    Commencer la formation
                                </button>
                                <button class="bg-yellow-400 text-gray-900 px-6 py-3 rounded-md font-medium hover:bg-yellow-500 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                                    Sauvegarder
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</UserLayout>
</template>

<style scoped>
/* Transitions fluides */
* {
    transition: all 0.2s ease-in-out;
}

/* Focus states pour l'accessibilité */
button:focus,
button:focus-visible {
    outline: 2px solid #1e40af;
    outline-offset: 2px;
}
</style>
