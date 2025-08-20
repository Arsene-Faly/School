<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();

function goBack() {
    window.history.back();
}
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10" id="section">
        <div class="w-full max-w-2xl">
            <div class="flex flex-col gap-8 space-y-0 bg-gray-50 p-3 sm:p-6 sm:rounded-3xl shadow-2xl">
                <div class="flex flex-col items-center gap-6">
                    <div class="flex justify-end w-full">
                        <button @click="goBack" type="button"
                            class="cursor-pointer inline-flex items-center rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                            <ChevronLeftIcon class="w-5 h-5" />
                            <span>Retour</span>
                        </button>
                    </div>
                    <div class="space-y-3 text-center mt-0">
                        <h1 id="title"
                            class="relative mb-3 inline-block bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-5xl font-extrabold tracking-tight text-transparent drop-shadow-lg">
                            {{ title }}
                        </h1>
                    </div>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
