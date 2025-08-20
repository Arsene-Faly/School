<script setup>
import {
  AcademicCapIcon,
  BookOpenIcon,
  UsersIcon,
  CheckCircleIcon,
  ChartBarIcon,
  LifebuoyIcon
} from '@heroicons/vue/24/solid'

const services = [
  {
    title: 'Répertoire des écoles',
    description: 'Parcourez une base de données complète d’établissements scolaires et universitaires.',
    icon: AcademicCapIcon
  },
  {
    title: 'Catalogue de formations',
    description: 'Trouvez la formation idéale selon votre domaine, niveau ou objectif professionnel.',
    icon: BookOpenIcon
  },
  {
    title: 'Orientation & Conseils',
    description: 'Recevez des recommandations personnalisées selon votre profil et vos ambitions.',
    icon: UsersIcon
  },
  {
    title: 'Suivi des candidatures',
    description: 'Créez un compte, postulez à des écoles et suivez l’état de vos demandes en temps réel.',
    icon: CheckCircleIcon
  },
  {
    title: 'Comparateur d’écoles',
    description: 'Comparez les établissements selon les avis, le coût, la localisation et les taux de réussite.',
    icon: ChartBarIcon
  },
  {
    title: 'Assistance & support',
    description: 'Notre équipe vous accompagne pour répondre à toutes vos questions et besoins spécifiques.',
    icon: LifebuoyIcon
  }
]

defineProps({
    active: true
})

</script>

<template>
  <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
    <div
      v-for="(service, index) in services"
      :key="index"
      class="group cursor-pointer rounded-2xl bg-white p-6 shadow-md ring-1 ring-gray-200 transition-all duration-300 hover:shadow-xl hover:ring-indigo-200"
    >
      <!-- Icône -->
      <div
        class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition duration-300 group-hover:bg-indigo-100 group-hover:text-indigo-700"
      >
        <component :is="service.icon" class="w-7 h-7" />
      </div>

      <!-- Titre -->
      <h4 class="mb-2 text-xl font-semibold text-gray-900 group-hover:text-indigo-600 transition">
        {{ service.title }}
      </h4>

      <!-- Description -->
      <p class="text-base text-gray-600 sm:text-lg leading-relaxed">
        {{ service.description }}
      </p>
    </div>
  </div>

  <slot />
</template>
