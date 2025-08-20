<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import SchoolHeader from './page/SchoolHeader.vue';
import SchoolMaps from './page/SchoolMaps.vue';
import Gallery from './page/Gallery.vue';
import SchoolTestimonials from './page/SchoolTestimonials.vue';
import Formation from './page/Formation.vue';
import About from './page/About.vue';
import Activity from './page/Activity.vue';

const props = defineProps({
    school: {
        type: Object,
        default: null,
    },
    average_rating: Number,
    gallery: {
        type: Array,
        default: () => [],
    },
    isFavorited: Boolean
});

console.log("Favorie :" + props.isFavorited)
</script>

<template>
    <UserLayout>
        <SectionDefault title="Details ecoles">
        </SectionDefault>

        <Section name="Espace de gestion de votre établissement"
            description="Consultez un aperçu complet et structuré de votre établissement.">
            <SchoolHeader :school="props.school" :average_rating="props.average_rating" :isFavorited="props.isFavorited" />

            <About :school="props.school" />

            <SchoolMaps :school="props.school" />

            <Gallery :gallery="props.gallery" />

            <Formation :school="props.school" />

            <Activity :school="props.school" />

            <SchoolTestimonials :school="props.school" />
        </Section>

    </UserLayout>
</template>
