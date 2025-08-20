<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import Card from './page/Card.vue';


</script>

<template>
    <UserLayout>
        <SectionDefault title="Bienvenue dans votre ecole">
        </SectionDefault>

        <Section name="Espace de gestion de votre établissement"
            description="Centralisez ici toutes les actions liées à la gestion, la personnalisation et le suivi de votre école.">
            <Card />
        </Section>

    </UserLayout>
</template>
