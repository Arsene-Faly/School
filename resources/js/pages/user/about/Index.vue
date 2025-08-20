<script setup lang="ts">
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import SectionService from '../home/page/SectionService.vue';
import TestimonialsForm from './page/TestimonialsForm.vue';
import Equipe from './page/Equipe.vue';
import { usePage } from '@inertiajs/vue3';

interface User {
    id: number
    name: string
    email: string
    role?: string | null
}

const page = usePage()
const user = page.props.auth.user as User
</script>

<template>
    <UserLayout>
        <SectionDefault title="Page d'Apropos">
        </SectionDefault>

        <Section name="Nos Equipes" description="Notre plateforme vous permet d'explorer les écoles
          les mieux notées, selon leur réputation, localisation et domaines
          d'excellence.">
            <Equipe />
        </Section>

        <Section id="services" name="Nos Services" description="Explorez les écoles, les formations, et les services qui vous ouvrent les portes de la réussite.
">

            <SectionService>
            </SectionService>
        </Section>

        <div v-if="user">
            <Section id="testimonials" name="Témoinnage" description="Explorez les écoles, les formations, et les services qui vous ouvrent les portes de la réussite.">
                <TestimonialsForm />
            </Section>
        </div>
    </UserLayout>
</template>
