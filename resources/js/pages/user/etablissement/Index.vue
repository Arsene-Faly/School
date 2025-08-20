<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import List from './page/List.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});

</script>

<template>
    <UserLayout>
        <SectionDefault :title="`Page D' Etablissement ${props.data.categorie}`">
        </SectionDefault>

        <Section :name="`Les établissements ${props.data.categorie}`">
            <List :data="props.data"/>
        </Section>
    </UserLayout>
</template>
