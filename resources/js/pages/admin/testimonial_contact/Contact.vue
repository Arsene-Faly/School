<script setup>
import SidebarLayout from '@/components/app/admin/SidebarLayout.vue';
import List from './page/List1.vue';

const props = defineProps({
    data: Object,
});

</script>

<template>
    <SidebarLayout userRole="admin" welcome="Gestion validation d'école" activePage="Testimonial"
        desc="All systems are running smoothly! You have 3 unread">
    <List :data="data"/>
    </SidebarLayout>
</template>
