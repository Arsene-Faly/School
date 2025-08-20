<script setup>
import {
    UserGroupIcon,
    AcademicCapIcon,
    BuildingOfficeIcon,
    MagnifyingGlassIcon,
    FireIcon,
    GlobeAltIcon,
    StarIcon,
    BoltIcon,
    ChatBubbleBottomCenterTextIcon,
    EyeSlashIcon,
} from '@heroicons/vue/24/outline'
import img from '/public/assets/image/people.png'
import { Eye, UserIcon, UserPlus2, View } from 'lucide-vue-next';
import { ChatBubbleBottomCenterIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    totalViews: Number
});

// console.log(props.totalViews)
const stats = [
    {
        title: 'Utilisateurs',
        value: props.data.countUser,
        subtitle: '+ ' + props.data.userWeekCount + ' créer cette semaine',
        icon: UserGroupIcon,
        color: 'border-blue-500',
    },
    {
        title: 'Propriétaire',
        value: props.data.countOwner,
        subtitle: '+ ' + props.data.ownerWeekCount + ' créer cette semaine',
        icon: UserPlus2,
        color: 'border-blue-500',
    },
    {
        title: 'Propriétaire En attente',
        value: props.data.countOwnerPending,
        subtitle: '+ ' + props.data.ownerendingWeekCount + ' créer cette semaine',
        icon: UserIcon,
        color: 'border-blue-500',
    },
    {
        title: 'Écoles',
        value: props.data.countSchool,
        subtitle: '+ ' + props.data.countSchoolWeek + ' créer cette semaine',
        icon: BuildingOfficeIcon,
        color: 'border-yellow-500',
    },
    {
        title: 'Écoles en attente',
        value: props.data.countSchoolPending,
        subtitle: '+ ' + props.data.countSchoolPendingWeek + ' créer cette semaine',
        icon: BuildingOfficeIcon,
        color: 'border-yellow-500',
    },
    {
        title: 'Avis des utilisateurs',
        value: props.data.CountAvis,
        subtitle: '+ ' + props.data.CountAvisWeek + ' créer cette semaine',
        icon: ChatBubbleBottomCenterTextIcon,
        color: 'border-green-500',
    },
    {
        title: 'Message pour le site',
        value: props.data.ContactSite,
        subtitle: '+ ' + props.data.ContactSiteWeek + ' créer cette semaine',
        icon: ChatBubbleBottomCenterTextIcon,
        color: 'border-green-500',
    },
    {
        title: 'Views',
        value: props.totalViews,
        // subtitle: '+210 cette semaine',
        icon: Eye,
        color: 'border-indigo-500',
    },
    {
        title: 'Formations',
        value: '87',
        subtitle: '+4 nouvelles ajoutées',
        icon: AcademicCapIcon,
        color: 'border-green-500',
    },
    {
        title: 'Formation Populaire',
        value: 'Marketing Digital',
        subtitle: '540 vues',
        icon: FireIcon,
        color: 'border-red-500',
    },
    {
        title: 'École la plus consultée',
        value: 'HEC Paris',
        subtitle: '1.2K visites',
        icon: GlobeAltIcon,
        color: 'border-orange-400',
    },
    {
        title: 'Note moyenne',
        value: '4.3 ⭐',
        subtitle: 'sur 5 (écoles + formations)',
        icon: StarIcon,
        color: 'border-purple-500',
    },
    {
        title: 'Utilisateurs actifs',
        value: '312',
        subtitle: 'dernières 24h',
        icon: BoltIcon,
        color: 'border-teal-500',
    },
];
</script>

<template>
    <div class="grid gap-6 p-6 bg-gray-100 rounded-2xl mt-10
           grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
           auto-rows-fr">
        <div class="col-span-1 sm:col-span-2 sm:row-span-2 lg:col-span-2 lg:row-span-2
             rounded-2xl overflow-hidden shadow-xl relative group">
            <img :src="img" alt="Dashboard Illustration"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 border border-gray-100 rounded-2xl" />
            <div class="absolute inset-0 flex flex-col justify-end p-6 bg-gradient-to-t from-black/60 to-transparent">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white drop-shadow-xl">
                    Statistiques du système
                </h2>
                <p class="text-white mt-3 text-sm sm:text-base">
                    Suivi des recherches, écoles, formations, utilisateurs actifs et
                    notes moyennes en temps réel.
                </p>
            </div>
        </div>

        <template v-for="(stat, index) in stats" :key="index">
            <div class="cursor-pointer bg-gray-50 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex gap-4 items-center p-5 border-l-4"
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
    </div>
</template>
