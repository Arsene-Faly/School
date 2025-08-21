<script setup>
import Show from '@/pages/owner/owner/formation/page/Show.vue';

const props = defineProps({
    formation: Object
});
</script>

<template>
    <Show :formation="formation" />
</template>
