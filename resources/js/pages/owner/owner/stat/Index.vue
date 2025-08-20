<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { AcademicCapIcon, BookOpenIcon, CalendarIcon } from '@heroicons/vue/24/outline';
import { Eye, MessageCircleMore, RemoveFormatting, School, User } from 'lucide-vue-next';

const props = defineProps({
    data: Object,
});

// console.log(props.school)

const school = {
    stats: [
        {
            icon: CalendarIcon,
            label: "Fondée en",
            value: props.data?.school.info.date_creation ?? 0,
        },
        {
            icon: User,
            label: "Nombre étudiant",
            value: props.data?.school.info.nombre_etudiants ?? 0,
        },
        {
            icon: Eye,
            label: 'Nombre de views pour votre école',
            value: props.data?.school.views ?? 0,
        },
        {
            icon: MessageCircleMore,
            label: 'Nombre témoinage publié pour votre école',
            value: props.data?.count_testimonial_true ?? 0,
        },
        {
            icon: MessageCircleMore,
            label: 'Nombre témoinage en attente pour votre école',
            value: props.data?.count_testimonial_false ?? 0,
        },
        {
            icon: AcademicCapIcon,
            label: 'Nombre Formation publié pour votre école',
            value: props.data?.count_formation_published ?? 0,
        },
        {
            icon: AcademicCapIcon,
            label: 'Nombre Formation en attente pour votre école',
            value: props.data?.count_formation_draft ?? 0,
        },
        {
            icon: AcademicCapIcon,
            label: 'Nombre Formation archivée pour votre école',
            value: props.data?.count_formation_archived ?? 0,
        },
        {
            icon: BookOpenIcon,
            label: 'Nombre Activity publié pour votre école',
            value: props.data?.count_Activity_published ?? 0,
        },
        {
            icon: BookOpenIcon,
            label: 'Nombre Activity en attente pour votre école',
            value: props.data?.count_Activity_draft ?? 0,
        },
        {
            icon: BookOpenIcon,
            label: 'Nombre Activity archivée pour votre école',
            value: props.data?.count_Activity_archived ?? 0,
        },
    ],
};
</script>

<template>
    <UserLayout>
        <SectionDefault title="page de Statistique">
        </SectionDefault>

        <Section name="Vue d’ensemble de la statistique de l’école"
            description="Ce tableau vous présente les données clés de votre établissement.">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(stat, index) in school.stats" :key="index"
                    class="relative bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-6 shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition duration-300">
                    <!-- Icône en arrière-plan -->
                    <div class="absolute right-4 top-4 opacity-80">
                        <component :is="stat.icon" class="w-20 h-20 text-indigo-400" />
                    </div>

                    <!-- Contenu principal -->
                    <div class="flex items-center space-x-4 relative z-10">
                        <div class="flex-shrink-0 bg-indigo-500/20 p-4 rounded-full">
                            <component :is="stat.icon" class="w-8 h-8 text-indigo-600" />
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm uppercase tracking-wide">
                                {{ stat.label }}
                            </p>
                            <p class="text-3xl font-extrabold text-gray-900 mt-1">
                                {{ stat.value }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </Section>
    </UserLayout>
</template>
