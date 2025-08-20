<script setup>
import TextLink from '@/components/TextLink.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    count: Object
});

const current = route().current()

</script>

<template>
    <div class="flex flex-wrap gap-2 sm:gap-4 overflow-x-auto border-b border-gray-100
           md:flex-nowrap md:overflow-visible py-2">
        <!-- Écoles validées -->
        <TextLink :href="route('admin.ecole')" id="ecoles-validees-tab" :class="[
            current === 'admin.ecole'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'flex items-center gap-2 whitespace-nowrap px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-medium transition-all duration-150'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#4A3283]" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Écoles validées</span>
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs sm:text-sm font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.count.countEcole }}
            </span>
        </TextLink>

        <!-- Écoles en attente -->
        <TextLink :href="route('admin.ecole.pending')" id="ecoles-attente-tab" :class="[
            current === 'admin.ecole.pending'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'flex items-center gap-2 whitespace-nowrap px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-medium transition-all duration-150'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#4A3283]" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Écoles en attente</span>
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs sm:text-sm font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.count.countEcolePending }}
            </span>
        </TextLink>
    </div>
</template>
