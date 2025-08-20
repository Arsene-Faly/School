<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import About from '@/pages/owner/owner/school/page/About.vue';
import Formation from '@/pages/owner/owner/school/page/Formation.vue';
import Gallery from '@/pages/owner/owner/school/page/Gallery.vue';
import SchoolHeader from '@/pages/owner/owner/school/page/SchoolHeader.vue';
import SchoolMaps from '@/pages/owner/owner/school/page/SchoolMaps.vue';
import SchoolTestimonials from '@/pages/owner/owner/school/page/SchoolTestimonials.vue';
import { onMounted } from 'vue';

const props = defineProps({
    schools: {
        type: Object,
        default: () => ({}),
    },
    average_rating: Number,
    user_rating: Number,
    gallery: {
        type: Array,
        default: () => [],
    },
    isFavorited: Boolean
});

const ecoles = props.schools ?? [];

onMounted(() => {
  if (!window.userLoggedIn) { // variable JS à définir côté blade ou Inertia pour dire si connecté
    const key = `viewed_school_${props.schools.id}`;
    if (!localStorage.getItem(key)) {
      fetch(`/schools/${props.schools.id}/increment-views`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
      }).then(() => {
        localStorage.setItem(key, 'true');
      });
    }
  }
});
</script>

<template>
    <UserLayout>
        <SectionDefault :title="`Page D' Etablissement`">
        </SectionDefault>

        <Section :name="`${ecoles.name}`">
            <SchoolHeader :school="props.schools" :average_rating="props.average_rating" :isFavorited="isFavorited"/>
            <About :school="props.schools" />
            <SchoolMaps :school="props.schools" />
            <Gallery :gallery="props.gallery"/>
            <Formation :school="props.schools" />
            <SchoolTestimonials :school="props.schools"/>
        </Section>

    </UserLayout>
</template>
