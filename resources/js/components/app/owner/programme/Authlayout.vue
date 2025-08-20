<script setup>
import TextLink from '@/components/TextLink.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    data: Object
});

const current = route().current();
</script>

<template>
    <div
        class="flex overflow-x-auto border-b border-gray-100 md:space-x-4 w-full scrollbar-hide">

        <!-- Publiées -->
        <TextLink
            :href="route('owner.formation.status', 'published')"
            id="tab-published"
            :class="[
                current === 'owner.formation.status' && $page.url.includes('published')
                    ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                    : 'text-gray-500 hover:text-[#4A3283]',
                'flex items-center whitespace-nowrap px-4 md:px-6 py-2 md:py-3 text-sm md:text-lg font-medium'
            ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-5 w-5 text-[#4A3283]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Publiées
            <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 text-xs md:text-sm font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.data.counts.published }}
            </span>
        </TextLink>

        <!-- En attente -->
        <TextLink
            :href="route('owner.formation.status', 'draft')"
            id="tab-draft"
            :class="[
                current === 'owner.formation.status' && $page.url.includes('draft')
                    ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                    : 'text-gray-500 hover:text-[#4A3283]',
                'flex items-center whitespace-nowrap px-4 md:px-6 py-2 md:py-3 text-sm md:text-lg font-medium'
            ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-5 w-5 text-[#4A3283]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            En attente
            <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 text-xs md:text-sm font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.data.counts.draft }}
            </span>
        </TextLink>

        <!-- Archivées -->
        <TextLink
            :href="route('owner.formation.status', 'archived')"
            id="tab-archived"
            :class="[
                current === 'owner.formation.status' && $page.url.includes('archived')
                    ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                    : 'text-gray-500 hover:text-[#4A3283]',
                'flex items-center whitespace-nowrap px-4 md:px-6 py-2 md:py-3 text-sm md:text-lg font-medium'
            ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-5 w-5 text-[#4A3283]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Archivées
            <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 text-xs md:text-sm font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.data.counts.archived }}
            </span>
        </TextLink>
    </div>
</template>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
