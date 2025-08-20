<script setup>
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import ProfileUser from './page/ProfileUsers.vue';


</script>

<template>
    <UserLayout>
        <SectionDefault title="Page De Profile">
        </SectionDefault>

        <ProfileUser />
    </UserLayout>
</template>
