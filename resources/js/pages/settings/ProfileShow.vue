<script setup>
import SidebarLayout from '@/components/app/Admin/SidebarLayout.vue';
import { usePage } from '@inertiajs/vue3'
import Show from './Settings/Show.vue';

const page = usePage()

const rawRole = page.props.auth.user.role
const userRole = rawRole === 'pending_owner' ? 'owner_pending' : rawRole
</script>

<template>
    <SidebarLayout activePage="parametre" :userRole="userRole">
        <Show />
    </SidebarLayout>
</template>
