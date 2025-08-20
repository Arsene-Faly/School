<script setup>
import SidebarLayout from '@/components/app/admin/SidebarLayout.vue';
import Card from './page/Card.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    totalViews: Number
});

// console.log(props.totalViews)
</script>

<template>
    <SidebarLayout userRole="admin" welcome="Bienvenue dans le tableau de bord Administrateur" activePage="dashboard"
        desc="All systems are running smoothly! You have 3 unread">
        <Card :data="props.data" :totalViews="props.totalViews" />
    </SidebarLayout>
</template>
