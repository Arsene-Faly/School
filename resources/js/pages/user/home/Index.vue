<script setup lang="ts">
import Section from '@/components/app/user/section/Section.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import HeroSection from './page/HeroSection.vue';
import SectionSchool from './page/SectionSchool.vue';
import SectionProgram from './page/SectionProgram.vue';
import SectionTestimonials from './page/SectionTestimonials.vue';
import SectionAbout from './page/SectionAbout.vue';
import SectionService from './page/SectionService.vue';
import { usePage } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';

interface User {
    id: number
    name: string
    email: string
    role?: string | null
}

const page = usePage()
const user = page.props.auth.user as User

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    }
})

</script>

<template>
    <UserLayout>
        <HeroSection />

        <div v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'">
            <Section name="Écoles Populaires"
                description="Découvrez les établissements les plus plébiscités par notre communauté d'étudiants">
                <SectionSchool :data="props.data" />
            </Section>

            <Section name="Activité et Programme"
                description="Découvrez les établissements les plus plébiscités par notre communauté d'étudiants">
                <SectionProgram :data="props.data" />
            </Section>
        </div>

        <Section name="Avis des étudiants" description="Découvrez les avis des étudiants">
            <SectionTestimonials :data="props.data" />
        </Section>

        <Section name="A propos de nous"
            description="Votre avenir commence ici, avec une équipe qui croit en votre potentiel">
            <SectionAbout />
        </Section>

        <Section name="Nos Services" description="Une plateforme pour guider votre avenir éducatif">
            <SectionService>
                <div class="mx-auto w-full flex justify-center mt-10">
                    <TextLink :href="route('about')"
                        class="inline-block rounded-xl bg-indigo-600 px-8 py-3 text-lg font-semibold text-white transition-colors duration-300 hover:bg-indigo-700">
                        Voir Tous
                    </TextLink>
                </div>
            </SectionService>
        </Section>
    </UserLayout>
</template>
