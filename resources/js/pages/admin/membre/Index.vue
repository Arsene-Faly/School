<script setup>
import ListUser from './page/ListUser.vue';
import ListOwner from './page/ListOwner.vue';
import ListOwnerPending from './page/ListOwnerPending.vue';

import { computed } from 'vue';
import SidebarLayout from '@/components/app/admin/SidebarLayout.vue';

const props = defineProps({
    users: Object,
    filters: Object,
    roleCounts: Object,
    currentRole: String,
});

const List = computed(() => {
    if (props.currentRole === 'user') return ListUser;
    if (props.currentRole === 'owner') return ListOwner;
    if (props.currentRole === 'pending_owner') return ListOwnerPending;
    return ListUser; // fallback
});
</script>

<template>
    <SidebarLayout userRole="admin" welcome="Gestion des membres" activePage="membre"
        desc="All systems are running smoothly! You have 3 unread">
        <component :is="List" :users="props.users" :filters="props.filters" :roleCounts="props.roleCounts" />
    </SidebarLayout>
</template>
