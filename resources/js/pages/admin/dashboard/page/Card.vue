<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';
import {
  UserGroupIcon,
  BuildingOfficeIcon,
  ChatBubbleBottomCenterTextIcon,
  StarIcon,
  EyeIcon,
} from '@heroicons/vue/24/outline';
import { Eye, UserIcon, UserPlus2 } from 'lucide-vue-next';
import img from '/public/assets/image/people.png';

// Enregistrement Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

interface SchoolStat {
  name: string;
  average_rating?: number;
  views?: number;
}

interface DashboardData {
  countUser: number;
  userWeekCount: number;
  countOwner: number;
  ownerWeekCount: number;
  countOwnerPending: number;
  ownerendingWeekCount: number;
  countSchool: number;
  countSchoolWeek: number;
  countSchoolPending: number;
  countSchoolPendingWeek: number;
  CountAvis: number;
  CountAvisWeek: number;
  ContactSite: number;
  ContactSiteWeek: number;
  mostRatedSchool: SchoolStat;
  mostViewedSchool: SchoolStat;
  userTrendLabels: string[];
  userTrendData: number[];
  schoolTrendData: number[];
  avisTrendData: number[];
}

const props = defineProps<{
  data: DashboardData;
  totalViews: number;
}>();

// Statistiques à afficher
const stats = computed(() => [
  {
    title: 'Utilisateurs',
    value: props.data.countUser || 0,
    subtitle: `+ ${props.data.userWeekCount || 0} créés cette semaine`,
    icon: UserGroupIcon,
    color: 'border-blue-500',
  },
  {
    title: 'Propriétaires',
    value: props.data.countOwner || 0,
    subtitle: `+ ${props.data.ownerWeekCount || 0} créés cette semaine`,
    icon: UserPlus2,
    color: 'border-blue-500',
  },
  {
    title: 'Propriétaires en attente',
    value: props.data.countOwnerPending || 0,
    subtitle: `+ ${props.data.ownerendingWeekCount || 0} créés cette semaine`,
    icon: UserIcon,
    color: 'border-blue-500',
  },
  {
    title: 'Écoles',
    value: props.data.countSchool || 0,
    subtitle: `+ ${props.data.countSchoolWeek || 0} créées cette semaine`,
    icon: BuildingOfficeIcon,
    color: 'border-yellow-500',
  },
  {
    title: 'Écoles en attente',
    value: props.data.countSchoolPending || 0,
    subtitle: `+ ${props.data.countSchoolPendingWeek || 0} créées cette semaine`,
    icon: BuildingOfficeIcon,
    color: 'border-yellow-500',
  },
  {
    title: 'Avis des utilisateurs',
    value: props.data.CountAvis || 0,
    subtitle: `+ ${props.data.CountAvisWeek || 0} créés cette semaine`,
    icon: ChatBubbleBottomCenterTextIcon,
    color: 'border-green-500',
  },
  {
    title: 'Messages pour le site',
    value: props.data.ContactSite || 0,
    subtitle: `+ ${props.data.ContactSiteWeek || 0} créés cette semaine`,
    icon: ChatBubbleBottomCenterTextIcon,
    color: 'border-green-500',
  },
  {
    title: 'Views sur le site',
    value: props.totalViews || 0,
    icon: Eye,
    color: 'border-indigo-500',
  },
  {
    title: 'École la plus populaire',
    value: props.data.mostRatedSchool?.name || 'N/A',
    subtitle: `${props.data.mostRatedSchool?.average_rating || 0} étoiles`,
    icon: StarIcon,
    color: 'border-yellow-400',
  },
  {
    title: 'École la plus consultée',
    value: props.data.mostViewedSchool?.name || 'N/A',
    subtitle: `${props.data.mostViewedSchool?.views || 0} vues`,
    icon: EyeIcon,
    color: 'border-orange-400',
  },
]);

// Données graphiques pour Chart.js
import type { ChartData, ChartOptions } from 'chart.js';

const weeklyChartData: ChartData<'bar', number[], string> = {
  labels: props.data.userTrendLabels || [],
  datasets: [
    {
      label: 'Utilisateurs',
      data: props.data.userTrendData || [],
      backgroundColor: '#3b82f6',
    },
    {
      label: 'Écoles',
      data: props.data.schoolTrendData || [],
      backgroundColor: '#f59e0b',
    },
    {
      label: 'Avis',
      data: props.data.avisTrendData || [],
      backgroundColor: '#10b981',
    },
  ],
};

const weeklyChartOptions: ChartOptions<'bar'> = {
  responsive: true,
  plugins: {
    legend: { position: 'top' },
    title: { display: true, text: 'Tendances de la semaine' },
  },
};
</script>

<template>
  <div class="grid gap-6 p-6 bg-gray-100 rounded-2xl mt-10
              grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

    <!-- Image de présentation -->
    <div class="col-span-1 sm:col-span-2 sm:row-span-2 lg:col-span-2 lg:row-span-2
                rounded-2xl overflow-hidden shadow-xl relative group">
      <img :src="img" alt="Dashboard Illustration"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 border border-gray-100 rounded-2xl" />
      <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 to-transparent">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white drop-shadow-xl">
          Statistiques du système
        </h2>
        <p class="text-white mt-3 text-sm sm:text-base">
          Suivi des recherches, écoles, formations, utilisateurs actifs et notes moyennes en temps réel.
        </p>
      </div>
    </div>

    <!-- Cartes statistiques -->
    <template v-for="(stat, index) in stats" :key="index">
      <div class="cursor-pointer bg-gray-50 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1
                  transition-all duration-300 flex gap-4 items-center p-5 border-l-4"
           :class="stat.color">
        <component :is="stat.icon" class="w-10 h-10 sm:w-11 sm:h-11 text-indigo-700 shrink-0" />
        <div class="flex-1">
          <h3 class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
            {{ stat.title }}
          </h3>
          <div class="text-xl sm:text-2xl font-bold text-gray-900 mt-1 truncate">
            {{ stat.value }}
          </div>
          <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
            {{ stat.subtitle }}
          </p>
        </div>
      </div>
    </template>

    <!-- Graphique de la semaine -->
    <div class="col-span-1 sm:col-span-2 lg:col-span-4 bg-white p-4 rounded-2xl shadow-md">
      <Bar :data="weeklyChartData" :options="weeklyChartOptions" />
    </div>

  </div>
</template>
