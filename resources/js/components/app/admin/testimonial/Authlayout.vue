<script setup>
import TextLink from '@/components/TextLink.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    data: Object
});

const current = route().current()

</script>

<template>
    <div
        class="flex flex-wrap space-x-0 overflow-x-auto border-b border-gray-100 md:flex-nowrap md:space-x-4 md:overflow-visible">
        <!-- Écoles validées -->
        <TextLink :href="route('admin.testimonial')" id="gestion-utilisateur-tab" :class="[
            current === 'admin.testimonial'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'whitespace-nowrap px-6 py-3 text-lg font-medium'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-5 w-5 text-[#4A3283]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Les Témoinages
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-lg font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.data.countTestimonial }}
            </span>
        </TextLink>

        <TextLink :href="route('admin.contact')" id="gestion-utilisateur-tab" :class="[
            current === 'admin.contact'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'whitespace-nowrap px-6 py-3 text-lg font-medium'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-5 w-5 text-[#4A3283]" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Contacts
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-lg font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.data.countContact }}
            </span>
        </TextLink>
    </div>
</template>
