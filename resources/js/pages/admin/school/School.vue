<script setup>
import SidebarLayout from '@/components/app/admin/SidebarLayout.vue';
import ValidationEcole from './page/ValidationEcole.vue';

const props = defineProps({
    ecoles: Object,
    filters: Object,
    count: Object
});

</script>

<template>
    <SidebarLayout userRole="admin" welcome="Gestion validation d'école" activePage="ecole"
        desc="All systems are running smoothly! You have 3 unread">
        <ValidationEcole :ecoles="props.ecoles" :filters="props.filters" :count="count"  />
    </SidebarLayout>
</template>
