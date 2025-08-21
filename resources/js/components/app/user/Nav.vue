<script setup lang="ts">

import { computed, onMounted, onUnmounted, ref } from 'vue';
import TextLink from '../../TextLink.vue';
import { router, usePage } from '@inertiajs/vue3';
import chats from '/public/assets/image/discussion-a-bulles.png';
import img from '/public/assets/image/person1.png'
import {
    ChevronUpDownIcon,
    AcademicCapIcon,
    BuildingLibraryIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    HomeIcon,
    InformationCircleIcon,
    PhoneIcon,
    UserCircleIcon,
} from '@heroicons/vue/24/solid';
import { ArrowPathIcon, ChartBarIcon, DocumentTextIcon, RectangleStackIcon, SpeakerWaveIcon } from '@heroicons/vue/24/outline';
import { SchoolIcon, SpeakerIcon } from 'lucide-vue-next';

const isMenuOpen = ref(false);
const etablissementOpen = ref(false);
const programmeOpen = ref(false);
const isScrolled = ref(false);

let etablissementTimeout: ReturnType<typeof setTimeout> | null = null;
let programmeTimeout: ReturnType<typeof setTimeout> | null = null;

function toggleMenu() {
    isMenuOpen.value = !isMenuOpen.value;
    if (!isMenuOpen.value) {
        etablissementOpen.value = false;
        programmeOpen.value = false;
        clearTimeouts();
    }
}

interface UserInfo {
    phone?: string
    address?: string
    bio?: string
    avatar?: string
}

interface User {
    id: number
    name: string
    email: string
    role?: string | null
    info?: UserInfo
}

const page = usePage()
const user = page.props.auth.user as User

const avatar = computed(() => {
    return user.info?.avatar ? `/${user.info.avatar}` : img
})

function clearTimeouts() {
    if (etablissementTimeout) clearTimeout(etablissementTimeout);
    if (programmeTimeout) clearTimeout(programmeTimeout);
    etablissementTimeout = null;
    programmeTimeout = null;
}

function onEtablissementMouseEnter() {
    clearTimeout(etablissementTimeout!);
    etablissementOpen.value = true;
}
function onEtablissementMouseLeave() {
    etablissementTimeout = setTimeout(() => {
        etablissementOpen.value = false;
    }, 100);
}
function onProgrammeMouseEnter() {
    clearTimeout(programmeTimeout!);
    programmeOpen.value = true;
}
function onProgrammeMouseLeave() {
    programmeTimeout = setTimeout(() => {
        programmeOpen.value = false;
    }, 100);
}

function handleScroll() {
    isScrolled.value = window.scrollY > 100;
}
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const current = route().current()

const params = route().params || {};
console.log('Route actuelle :', current)

const isEtablissementActive = computed(() => {
    return current?.startsWith('etablissement.')
})

const isProgramme = computed(() => {
    return current?.startsWith('programme.')
})

const logout = () => {
    router.post(route('logout'));
};

interface CustomPageProps {
    auth: {
        user?: User;
        unreadCount?: number;
        unreadByUser?: Record<number, number>; // clé = sender_id
    };
    [key: string]: any;
}

const pages = usePage<CustomPageProps>();

// compteur global
const unreadCount = ref(pages.props.auth.unreadCount ?? 0);

// compteur par utilisateur
const unreadByUser = ref<Record<number, number>>(pages.props.auth.unreadByUser ?? {});

// WebSocket
let ws: WebSocket | null = null;

function connectWS() {
    if (ws) ws.close();
    ws = new WebSocket('ws://localhost:6001');

    ws.onopen = () => console.log('WS connecté pour notifications');
    ws.onmessage = (event) => {
        const msg = JSON.parse(event.data);

        if (msg.receiver_id === pages.props.auth.user?.id) {
            // incrémente compteur global
            unreadCount.value++;

            // incrémente compteur par expéditeur
            if (unreadByUser.value[msg.sender_id])
                unreadByUser.value[msg.sender_id]++;
            else
                unreadByUser.value[msg.sender_id] = 1;
        }
    };

    ws.onclose = () => setTimeout(connectWS, 1000);
}

onMounted(() => connectWS());
</script>

<template>
    <nav :class="[
        'z-50 w-full fixed top-0 bg-gradient-to-r from-blue-700 via-indigo-900 to-purple-900 shadow-lg'
    ]">
        <div class="mx-auto flex items-center justify-between px-4 py-6 sm:px-10 lg:px-20">
            <!-- Logo -->
            <TextLink :href="route('home')" class="flex items-center space-x-4">
                <span class="text-4xl font-bold tracking-wide text-white">
                    <span class="font text-yellow-400">Educ</span>
                </span>
            </TextLink>

            <!-- Menu Desktop -->
            <ul class="hidden items-center space-x-10 text-lg font-semibold text-white 2xl:flex">
                <li>
                    <TextLink :href="route('home')" :class="[
                        'flex items-center space-x-4',
                        current === 'home' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Accueil</TextLink>
                </li>

                <!-- Etablissement -->
                <li v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'" class="relative"
                    @mouseenter="onEtablissementMouseEnter" @mouseleave="onEtablissementMouseLeave">
                    <!-- Bouton principal -->
                    <button @click.prevent="etablissementOpen = !etablissementOpen" :class="[
                        'cursor-pointer flex items-center px-3 py-2',
                        isEtablissementActive ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400']">
                        Établissement
                        <ChevronDownIcon class="ml-1 h-4 w-4" />
                    </button>

                    <!-- Menu déroulant -->
                    <transition enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <div v-show="etablissementOpen"
                            class="absolute left-0 z-50 mt-2 w-64 rounded-lg bg-white text-indigo-900 shadow-lg ring-1 ring-black ring-opacity-10">
                            <!-- Triangle -->
                            <div class="absolute top-0 left-5 -mt-2 h-4 w-4 rotate-45 bg-white shadow-md"></div>

                            <!-- Liens de catégories -->
                            <TextLink :href="route('etablissement.categorie', 'scolaire')"
                                class="block px-5 py-3 hover:bg-indigo-100" @click="etablissementOpen = false">
                                Établissement scolaire
                            </TextLink>
                            <TextLink :href="route('etablissement.categorie', 'superieur')"
                                class="block px-5 py-3 hover:bg-indigo-100" @click="etablissementOpen = false">
                                Établissement supérieur
                            </TextLink>
                            <TextLink :href="route('etablissement.categorie', 'formation')"
                                class="block px-5 py-3 hover:bg-indigo-100" @click="etablissementOpen = false">
                                Centre de formation
                            </TextLink>
                            <TextLink :href="route('etablissement.categorie', 'specialisees')"
                                class="block px-5 py-3 hover:bg-indigo-100" @click="etablissementOpen = false">
                                Écoles spécialisées
                            </TextLink>
                        </div>
                    </transition>
                </li>

                <!-- Programme -->
                <li v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'" class="relative"
                    @mouseenter="onProgrammeMouseEnter" @mouseleave="onProgrammeMouseLeave">
                    <button @click.prevent="programmeOpen = !programmeOpen" :class="[
                        'cursor-pointer flex items-center px-3 py-2',
                        isProgramme ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400']">
                        Programme
                        <ChevronDownIcon class="ml-1 h-4 w-4" />
                    </button>
                    <transition enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <div v-show="programmeOpen"
                            class="ring-opacity-10 absolute left-0 z-50 mt-2 w-48 rounded-lg bg-white text-indigo-900 shadow-lg ring-1 ring-black">
                            <div class="absolute top-0 left-5 -mt-2 h-4 w-4 rotate-45 bg-white shadow-md"></div>
                            <TextLink :href="route('programme.categorie', 'formation')" @click="programmeOpen = false"
                                class="block px-5 py-3 hover:bg-indigo-100">Formation</TextLink>
                            <TextLink :href="route('programme.categorie', 'activite')" @click="programmeOpen = false"
                                class="block px-5 py-3 hover:bg-indigo-100">Activité</TextLink>
                        </div>
                    </transition>
                </li>

                <!-- Statu attente -->
                <li v-if="user?.role == 'pending_owner'">
                    <TextLink :href="route('owner.pending')" :class="[
                        'flex items-center space-x-4',
                        current === 'owner.pending' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Statut de la demande</TextLink>
                </li>

                <!-- Owner -->
                <li v-if="user?.role == 'owner'">
                    <TextLink :href="route('owner.stat')" :class="[
                        'flex items-center space-x-4',
                        current === 'owner.stat' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Statistique mon école</TextLink>
                </li>

                <li v-if="user?.role == 'owner'">
                    <TextLink :href="route('owner.home')" :class="[
                        'flex items-center space-x-4',
                        current === 'owner.home' || current === 'owner.about.school' || current === 'owner.edit.school' || current === 'owner.editInfo.school' || current === 'owner.show.school' || current === 'owner.gallery.school' || current === 'owner.testimonials.school' || (current === 'owner.formation.status' && ['published', 'draft', 'archived'].includes(params.status)) || current === 'owner.formation.add' || current === 'owner.formation.show' || current === 'owner.formation.edit' || (current === 'owner.activity.status' && ['published', 'draft', 'archived'].includes(params.status)) || current === 'owner.activity.add' || current === 'owner.activity.show' || current === 'owner.activity.edit' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Mon école</TextLink>
                </li>

                <li v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'">
                    <TextLink :href="route('user.favoris')" :class="[
                        'flex items-center space-x-4',
                        current === 'user.favoris' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Favoris</TextLink>
                </li>

                <li>
                    <TextLink :href="route('about')" :class="[
                        'flex items-center space-x-4',
                        current === 'about' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">À propos</TextLink>
                </li>

                <li>
                    <TextLink :href="route('contact')" :class="[
                        'flex items-center space-x-4',
                        current === 'contact' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">Contact</TextLink>
                </li>

                <li>
                    <TextLink :href="route('faq')" :class="[
                        'flex items-center space-x-4',
                        current === 'faq' ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400',
                    ]">FAQ</TextLink>
                </li>
            </ul>

            <div v-if="$page.props.auth.user" class="hidden space-x-4 2xl:flex">
                <TextLink :href="route('profile.user') + '#profile'">
                    <div class="hidden sm:flex items-center max-w-[180px] text-white truncate">
                        <img :src="avatar" alt="Photo de profil"
                            class="ml-3 w-10 h-10 rounded-full object-cover border-2 border-white shadow" />
                        <span class="text-sm font-medium truncate ml-1">
                            {{ $page.props.auth.user.email }}
                        </span>
                    </div>
                </TextLink>
                <button @click="logout"
                    class="cursor-pointer rounded-md bg-yellow-400 px-5 py-2 font-bold text-indigo-900 hover:bg-yellow-300">Déconnexion</button>
            </div>
            <!-- Desktop boutons -->
            <div v-else class="hidden space-x-4 2xl:flex">
                <TextLink :href="route('login')"
                    class="cursor-pointer rounded-md border border-white px-5 py-2 text-white hover:bg-white hover:text-indigo-900">
                    Connexion</TextLink>
                <TextLink :href="route('register')"
                    class="cursor-pointer rounded-md bg-yellow-400 px-5 py-2 font-bold text-indigo-900 hover:bg-yellow-300">
                    Inscription</TextLink>
            </div>

            <button @click="toggleMenu"
                class="rounded-md p-3 text-white hover:bg-indigo-600 focus:ring-2 focus:ring-yellow-400 2xl:hidden">
                <svg v-if="!isMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
                <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu mobile -->
        <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
            <div v-show="isMenuOpen" class="bg-indigo-900 px-4 pb-6 2xl:hidden">
                <ul class="space-y-3 py-4 text-[17px] font-semibold text-white">
                    <li>
                        <TextLink :href="route('home')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'home'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <HomeIcon class="h-5 w-5" />
                            <span>Accueil</span>
                        </TextLink>
                    </li>

                    <li v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'">
                        <button @click="etablissementOpen = !etablissementOpen"
                            :class="['flex w-full items-center justify-between rounded-xl px-4 py-3'
                                , current?.startsWith('etablissement.') ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400']">
                            <div class="flex items-center gap-3">
                                <BuildingLibraryIcon class="h-5 w-5" /> <span class="">Établissement</span>
                            </div>
                            <ChevronDownIcon :class="{ 'rotate-180': etablissementOpen }"
                                class="h-5 w-5 transition-transform duration-300" />
                        </button>
                        <ul v-show="etablissementOpen" class="mt-1 ml-8 space-y-2">
                            <li>
                                <TextLink :href="route('etablissement.categorie', 'scolaire')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Etablissement scolaire
                                </TextLink>
                            </li>
                            <li>
                                <TextLink :href="route('etablissement.categorie', 'superieur')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Etablissement supérieur
                                </TextLink>
                            </li>
                            <li>
                                <TextLink :href="route('etablissement.categorie', 'formation')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Centres de formation
                                </TextLink>
                            </li>
                            <li>
                                <TextLink :href="route('etablissement.categorie', 'specialisees')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Écoles spécialisées
                                </TextLink>
                            </li>
                        </ul>
                    </li>

                    <li v-if="user?.role !== 'pending_owner' && user?.role !== 'owner'">
                        <button @click="programmeOpen = !programmeOpen"
                            :class="['flex w-full items-center justify-between rounded-xl px-4 py-3'
                                , current?.startsWith('programme.') ? 'font-bold text-yellow-400' : 'text-white hover:text-yellow-400']">
                            <div class="flex items-center gap-3">
                                <AcademicCapIcon class="h-5 w-5" /> <span>Programme</span>
                            </div>
                            <ChevronDownIcon :class="{ 'rotate-180': programmeOpen }"
                                class="h-5 w-5 transition-transform duration-300" />
                        </button>
                        <ul v-show="programmeOpen" class="mt-1 ml-8 space-y-2">
                            <li>
                                <TextLink :href="route('programme.categorie', 'formation')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Formation
                                </TextLink>
                            </li>
                            <li>
                                <TextLink :href="route('programme.categorie', 'activite')"
                                    class="flex items-center gap-2 rounded-lg px-4 py-2 hover:bg-yellow-300 hover:text-indigo-900">
                                    <ChevronRightIcon class="h-4 w-4" /> Activité
                                </TextLink>
                            </li>
                        </ul>
                    </li>

                    <!-- Statu attente -->
                    <li v-if="user?.role == 'pending_owner'">
                        <TextLink :href="route('owner.pending')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'owner.pending'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <ArrowPathIcon class="h-5 w-5" />
                            <span>Statut de la demande</span>
                        </TextLink>
                    </li>

                    <!-- Owner -->
                    <li v-if="user?.role == 'owner'">
                        <TextLink :href="route('owner.stat')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'owner.stat'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <ChartBarIcon class="h-5 w-5" /> Statistique mon école
                        </TextLink>
                    </li>

                    <li v-if="user?.role == 'owner'">
                        <TextLink :href="route('owner.home')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'owner.home' || current === 'owner.about.school' || current === 'owner.edit.school' || current === 'owner.editInfo.school' || current === 'owner.show.school' || current === 'owner.gallery.school' || current === 'owner.testimonials.school' || (current === 'owner.formation.status' && ['published', 'draft', 'archived'].includes(params.status)) || current === 'owner.formation.add' || current === 'owner.formation.show' || current === 'owner.formation.edit' || (current === 'owner.activity.status' && ['published', 'draft', 'archived'].includes(params.status)) || current === 'owner.activity.add' || current === 'owner.activity.show' || current === 'owner.activity.edit'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <SchoolIcon class="h-5 w-5" /> Mon école
                        </TextLink>
                    </li>

                    <li>
                        <TextLink :href="route('user.favoris')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'user.favoris'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <RectangleStackIcon class="h-5 w-5" /> Favoris
                        </TextLink>
                    </li>

                    <li>
                        <TextLink :href="route('about')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'about'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <InformationCircleIcon class="h-5 w-5" /> À propos
                        </TextLink>
                    </li>

                    <li>
                        <TextLink :href="route('contact')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'contact'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <PhoneIcon class="h-5 w-5" /> Contact
                        </TextLink>
                    </li>

                    <li>
                        <TextLink :href="route('faq')" :class="[
                            'relative flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-300',
                            current === 'faq'
                                ? 'text-yellow-400'
                                : 'text-white hover:bg-yellow-400 hover:text-indigo-900',
                        ]">
                            <SpeakerIcon class="h-5 w-5" /> FAQ
                        </TextLink>
                    </li>

                    <li v-if="$page.props.auth.user" class="px-4 py-2">
                        <TextLink :href="route('profile.user') + '#profile'">
                            <div class="flex items-center gap-3">
                                <img :src="avatar" alt="Photo de profil"
                                    class="w-10 h-10 rounded-full object-cover border-2 border-white shadow" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">
                                        {{ $page.props.auth.user.email }}
                                    </p>
                                </div>
                            </div>
                        </TextLink>

                        <button @click="logout"
                            class="mt-3 w-full rounded-xl bg-yellow-400 px-4 py-2 text-sm font-bold text-indigo-900 hover:bg-yellow-300 transition">
                            Déconnexion
                        </button>
                    </li>

                    <span v-else>
                        <li>
                            <TextLink :href="route('login')"
                                class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-yellow-400 hover:text-indigo-900">
                                <UserCircleIcon class="h-5 w-5" /> Connexion
                            </TextLink>
                        </li>
                        <li>
                            <TextLink :href="route('register')"
                                class="block rounded-xl bg-yellow-400 px-4 py-3 text-center font-bold text-indigo-900 hover:bg-yellow-300">
                                Inscription</TextLink>
                        </li>
                    </span>
                </ul>
            </div>
        </transition>
    </nav>

    <div class="fixed bottom-4 right-4 z-50" v-if="$page.props.auth?.user">
        <TextLink :href="route('messages.index')"
            class="relative cursor-pointer w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center shadow-lg hover:bg-indigo-700 transition">
            <img :src="chats" alt="Chat" class="w-8 h-8" />
            <span v-if="unreadCount > 0"
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                <p>new</p>
            </span>
        </TextLink>
    </div>
</template>
