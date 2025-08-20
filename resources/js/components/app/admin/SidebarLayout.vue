<script setup>
import TextLink from '@/components/TextLink.vue'
import {
    Squares2X2Icon,
    HomeIcon,
    CalendarDaysIcon,
    Cog6ToothIcon,
    ArrowRightOnRectangleIcon,
    QuestionMarkCircleIcon,
    UserCircleIcon,
    PaperAirplaneIcon,
} from '@heroicons/vue/24/outline'
import { Link, router, usePage } from '@inertiajs/vue3'
import { ActivityIcon, BellIcon, MessageCircle, School, UserCircle, UserIcon } from 'lucide-vue-next'
import { ref, computed } from 'vue'

import img from '/public/assets/image/person1.png'
import { BuildingOfficeIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    userRole: String,
    activePage: String,
    welcome: String,
    desc: String,
})

const page = usePage();
const user = page.props.auth.user.info ?? '';

const roles = page.props.auth.user.role
const routeNotification = computed(() => {
    if (roles === 'admin') return route('admin.notifications')
    if (roles === 'pending_owner') return route('owner.notifications')
    return route('admin.notifications')
})


const unreadCount = computed(() => page.props.auth?.unreadNotificationsCount ?? 0)

const avatar = computed(() => {
    return user.avatar ? `/${user.avatar}` : img
})

const sidebarOpen = ref(false)

const linksByRole = {
    admin: [
        {
            name: 'dashboard',
            label: 'Tableau de bord',
            href: 'admin.dashboard',
            icon: HomeIcon,
        },
        {
            name: 'membre',
            label: 'Les membres',
            href: { name: 'admin.membre', params: { role: 'user' } },
            icon: UserCircle,
        },
        {
            name: 'ecole',
            label: 'Les ecoles',
            href: 'admin.ecole',
            icon: BuildingOfficeIcon,
        },
        {
            name: 'Testimonial',
            label: 'Les Interactions',
            href: 'admin.testimonial',
            icon: UserIcon,
        },
    ],
}



const filteredLinks = computed(() => linksByRole[props.userRole] || [])

const adjustedActivePage = computed(() => {
    if (props.activePage === 'parametre') return 'setting'
    return props.activePage
})

const activeLinkLabel = computed(() => {
    const links = linksByRole[props.userRole] || []
    const link = links.find(l => l.name === adjustedActivePage.value)
    return link?.label || 'Plus'
})

const today = new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
})

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <header class="fixed top-0 left-0 w-full h-16 bg-indigo-800 shadow z-50 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <Squares2X2Icon class="h-8 w-8 text-yellow-400" />
            <TextLink :href="route('home')" class="text-2xl font-extrabold text-yellow-400 tracking-wide font">Educ
            </TextLink>

            <div class="ml-10 hidden md:flex items-center gap-3">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <span class="text-white text-lg font-medium">
                    {{ activeLinkLabel }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <TextLink :href="routeNotification"
                class="relative rounded-full bg-white p-2 text-indigo-800 shadow-md hover:bg-indigo-100 transition duration-200">
                <BellIcon class="h-6 w-6" />
                <span v-if="unreadCount > 0"
                    class="absolute -top-1.5 -right-1.5 w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full flex items-center justify-center">{{
                        unreadCount }}</span>
            </TextLink>
            <div>
                <TextLink :href="route('profileNew.edit')"
                    class="hidden sm:flex items-center max-w-[180px] text-white truncate">
                    <span class="text-sm font-medium truncate">{{ $page.props.auth.user.email }}</span>
                    <img :src="avatar" alt="Photo de profil"
                        class="ml-3 w-10 h-10 rounded-full object-cover border-2 border-white shadow" />
                </TextLink>
            </div>
        </div>
    </header>

    <aside :class="[
        'fixed top-0 left-0 pt-20 h-screen bg-indigo-800 text-white shadow-xl z-40 flex flex-col transition-all duration-300',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'sm:w-[90px] md:w-[280px] md:translate-x-0'
    ]">
        <nav class="flex-1 px-3 space-y-1 text-sm font-bold">
            <template v-for="link in filteredLinks" :key="link.name">
                <TextLink :href="typeof link.href === 'string'
                    ? route(link.href)
                    : route(link.href.name, link.href.params)"
                    class="group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" :class="[
                        activePage === link.name
                            ? 'bg-gray-300 text-indigo-800 font-bold shadow opacity-90 hover:bg-gray-50'
                            : 'text-white hover:bg-gray-50 hover:text-indigo-800 opacity-85'
                    ]">
                    <component :is="link.icon" class="h-6 w-6" />
                    <span class="hidden md:inline">{{ link.label }}</span>
                </TextLink>
            </template>
        </nav>


        <div class="px-3 mt-auto mb-5 border-t border-indigo-700 pt-4 space-y-1 text-sm">
            <TextLink :href="route('profileNew.edit')"
                class="group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" :class="[
                    activePage === 'parametre'
                        ? 'bg-gray-300 text-indigo-800 font-bold shadow opacity-90 hover:bg-gray-50'
                        : 'text-white hover:bg-white hover:text-indigo-800 opacity-85'
                ]">
                <Cog6ToothIcon class="h-6 w-6" />
                <span class="hidden md:inline">Paramètres</span>
            </TextLink>
            <TextLink :href="route('faq.admin')"
                class="group flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200" :class="[
                    activePage === 'FAQ'
                        ? 'bg-gray-300 text-indigo-800 font-bold shadow opacity-90 hover:bg-gray-50'
                        : 'text-white hover:bg-white hover:text-indigo-800 opacity-85'
                ]">
                <QuestionMarkCircleIcon class="h-6 w-6" />
                <span class="hidden md:inline">FAQ</span>
            </TextLink>
            <Link href="" @click="logout"
                class="group flex items-center gap-3 px-4 py-3 rounded-lg bg-yellow-400 text-indigo-900 hover:bg-yellow-500 font-semibold">
            <ArrowRightOnRectangleIcon class="h-6 w-6" />
            <span class="hidden md:inline">Déconnexion</span>
            </Link>
        </div>
    </aside>

    <div v-if="sidebarOpen" class="fixed inset-0 z-30 md:hidden" @click="sidebarOpen = false" />

    <main class="bg-gray-100 min-h-screen pt-20 px-4 sm:ml-[10px] md:ml-[280px] transition-all duration-300">
        <div class="flex flex-col gap-2 h-full">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 ml-1">
                <h1 class="text-xl sm:text-2xl font-bold text-indigo-800">
                    {{ welcome }}
                </h1>
                <button type="button"
                    class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-800 text-white rounded-xl shadow hover:bg-indigo-700 transition duration-200 text-sm sm:text-base">
                    <CalendarDaysIcon class="w-5 h-5" />
                    <span>{{ today }}</span>
                </button>
            </div>
            <p class="text-base sm:text-lg font-light text-gray-800 ml-1">{{ desc }}</p>
        </div>

        <div class="px-1 sm:px-3 mt-4">
            <slot />
        </div>
    </main>
</template>
