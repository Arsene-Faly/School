<script setup>
defineProps({
    title: String
})
</script>

<template>
    <h2 class="text-2xl font-bold text-indigo-800 mb-5 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 12a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm2 0a6 6 0 1 1-12 0 6 6 0 0 1 12 0zm-6 6v2m0 0h6m-6 0H6" />
                </svg>
                <span> {{ title }}</span>
            </h2>
</template>
