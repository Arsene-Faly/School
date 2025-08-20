<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { ChatBubbleLeftIcon } from '@heroicons/vue/24/outline';
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
const props = defineProps({
    school: {
        type: Object,
        default: null,
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

const testimonials = computed(() => props.school?.schooltestimonials || []);
const currentIndex = ref(0);

// console.log(props.school)
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
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 p-4">
        <div class="relative">
            <h2 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center">
                <ChatBubbleLeftIcon class="w-6 h-6 text-indigo-600 mr-2" />
                Donner des avis à cette ecole
            </h2>

            <div v-if="testimonials == ''"
                class="flex flex-col items-center justify-center py-16 px-6 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-400 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10M7 16h4m-2 4h.01M12 20h9M3 4h18M4 8h16M4 16h8" />
                </svg>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Aucun témoignage trouvé</h1>

                <p class="text-gray-600 text-center max-w-sm">
                    Soyez le premier à partager votre expérience et à inspirer d’autres personnes.
                </p>

                <div v-if="user.role !== 'owner'" class="mt-10 flex justify-center">
                    <TextLink :href="route('etablissement.add.testimonial', {slug: props.school.slug})"
                        class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-base sm:text-lg font-semibold text-white shadow-md hover:bg-indigo-700 transition-colors duration-300">
                        Ajouter un témoignage
                    </TextLink>
                </div>
            </div>

            <div v-else class="relative w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-0 overflow-hidden">
                <div class="flex transition-transform duration-700 ease-in-out"
                    :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                    <div v-for="(testimonial, index) in testimonials" :key="index" class="min-w-full px-2 md:px-6">
                        <div
                            class="flex flex-col items-center gap-6 rounded-3xl bg-gray-100 border border-gray-200 p-6 sm:p-10 shadow-lg">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h.01M15 12h.01M6.5 17h11a2.5 2.5 0 002.5-2.5v-7A2.5 2.5 0 0017.5 5h-11A2.5 2.5 0 004 7.5v7A2.5 2.5 0 006.5 17z" />
                            </svg>

                            <p
                                class="text-center text-lg sm:text-xl md:text-2xl font-light text-gray-700 leading-relaxed">
                                "{{ testimonial.content }}"
                            </p>

                            <div class="text-center mt-4">
                                <p class="text-gray-900 font-semibold text-base sm:text-lg">
                                    {{ testimonial.user?.name || 'Utilisateur inconnu' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="prevSlide" aria-label="Précédent"
                    class="absolute top-1/2 left-3 sm:left-4 -translate-y-1/2 z-10 bg-white border border-gray-300 shadow-md hover:bg-indigo-50 text-indigo-600 rounded-full p-2 sm:p-3 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button @click="nextSlide" aria-label="Suivant"
                    class="absolute top-1/2 right-3 sm:right-4 -translate-y-1/2 z-10 bg-white border border-gray-300 shadow-md hover:bg-indigo-50 text-indigo-600 rounded-full p-2 sm:p-3 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="mt-10 flex justify-center" v-if="user.role !== 'owner'">
                    <TextLink :href="route('etablissement.add.testimonial', {slug: props.school.slug})"
                        class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-base sm:text-lg font-semibold text-white shadow-md hover:bg-indigo-700 transition-colors duration-300">
                        Ajouter un témoignage
                    </TextLink>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
