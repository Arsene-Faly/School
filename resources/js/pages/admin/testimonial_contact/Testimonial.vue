<script setup>
import SidebarLayout from '@/components/app/admin/SidebarLayout.vue';
import List from './page/List.vue';

const props = defineProps({
    data: Object,
});

console.log(props.data)

</script>

<template>
    <SidebarLayout userRole="admin" welcome="Gestion validation d'école" activePage="Testimonial"
        desc="All systems are running smoothly! You have 3 unread">
    <List :data="data"/>
    </SidebarLayout>
</template>
