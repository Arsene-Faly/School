<script setup>
import { CheckCircleIcon, TrashIcon } from '@heroicons/vue/24/solid'
import TextLink from '@/components/TextLink.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue'
import { debounce } from 'lodash-es'
import Input from '@/components/ui/input/Input.vue';

const current = route().current()

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    users: {
        type: Object,
        required: true,
    },
    onDelete: Function,
    filters: Object,
    roleCounts: Object,
    rout: {
        type: [String, Object],
        required: true,
    },
})

function goTopage(url) {
    if (url) {
        router.visit(url);
    }
}

const selectedUser = ref(null)

function openModal(user) {
    selectedUser.value = user
    console.log(selectedUser)
}

function closeModal() {
    selectedUser.value = null
}

const query = ref(props.filters?.search || '')
const active = ref(props.filters?.active ?? '')

function getRoute() {
    if (typeof props.rout === 'string') {
        return route(props.rout)
    }
    if (typeof props.rout === 'object' && props.rout.name) {
        return route(props.rout.name, props.rout.params || {})
    }
    throw new Error('Le paramètre "rout" est invalide')
}

function searchUsers() {
    router.get(getRoute(), {
        search: query.value,
        active: active.value,
    }, {
        preserveState: true,
        replace: true,
        only: ['users']
    })
}

const debouncedSearch = debounce(searchUsers, 500)
watch(query, () => debouncedSearch())
watch(active, () => searchUsers())

function formatDate(dateString) {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date)
}
</script>

<template>
    <div class="flex flex-wrap gap-2 overflow-x-auto border-b border-gray-200
           md:flex-nowrap md:gap-4 md:overflow-visible">
        <TextLink :href="route('admin.membre', { role: 'user' })" id="gestion-utilisateur-tab" :class="[
            current === 'admin.membre' && route().params.role === 'user'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'whitespace-nowrap px-4 sm:px-6 py-3 text-sm sm:text-base md:text-lg font-medium flex items-center gap-2'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#4A3283]" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Gestion Utilisateur</span>
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs sm:text-sm md:text-base font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.roleCounts.user }}
            </span>
        </TextLink>

        <TextLink :href="route('admin.membre', { role: 'owner' })" id="gestion-proprietaire-tab" :class="[
            current === 'admin.membre' && route().params.role === 'owner'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'whitespace-nowrap px-4 sm:px-6 py-3 text-sm sm:text-base md:text-lg font-medium flex items-center gap-2'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M18 9v6M15 12h6M12 14a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            <span>Gestion Propriétaire</span>
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs sm:text-sm md:text-base font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.roleCounts.owner }}
            </span>
        </TextLink>

        <TextLink :href="route('admin.membre', { role: 'pending_owner' })" id="gestion-proprietaire-pending-tab" :class="[
            current === 'admin.membre' && route().params.role === 'pending_owner'
                ? 'text-[#4A3283] border-b-2 border-[#4A3283]'
                : 'text-gray-500 hover:text-[#4A3283]',
            'whitespace-nowrap px-4 sm:px-6 py-3 text-sm sm:text-base md:text-lg font-medium flex items-center gap-2'
        ]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M12 14l9-5-9-5-9 5 9 5zM12 14v7M21 9v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7" />
            </svg>
            <span>Gestion Propriétaire en attente</span>
            <span
                class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs sm:text-sm md:text-base font-medium rounded-full bg-[#4A3283]/10 text-[#4A3283]">
                {{ props.roleCounts.pending_owner }}
            </span>
        </TextLink>
    </div>


    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 px-4">
        <div class="flex-1">
            <label for="search" class="block mb-1 text-sm font-medium text-gray-700">Recherche</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.15 6.15z" />
                </svg>
                <Input id="search" v-model="query" type="text" placeholder="Recherche par nom ou email"
                    class="w-full pl-10 pr-4 py-3 text-base text-gray-800 placeholder-gray-400 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
        </div>

        <div class="sm:w-64">
            <label for="status-filter" class="block mb-1 text-sm font-medium text-gray-700">Statut email</label>
            <select id="status-filter" v-model="active"
                class="w-full px-4 py-[0.3rem] text-base border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-gray-700">
                <option value="">-- Tous --</option>
                <option value="1">Vérifiés</option>
                <option value="0">Non vérifiés</option>
            </select>
        </div>
    </div>


    <div class="head">
        <table class="min-w-full table-auto">
            <thead class="thead">
                <tr>
                    <th v-for="column in columns" :key="column.key"
                        class="th">
                        {{ column.label }}
                    </th>
                    <th class="th">Actions</th>
                </tr>
            </thead>

            <tbody class="tbody">
                <tr v-for="(user, index) in users.data" :key="user.id"
                    class="hover:bg-purple-50 transition duration-200">
                    <td v-for="column in columns" :key="column.key" class="td">
                        <template v-if="column.key === 'active'">
                            <span :class="[
                                user.email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                                'inline-block px-3 py-1 text-[10px] sm:text-xs font-medium rounded-full'
                            ]">
                                {{ user.email_verified_at ? 'Actif' : 'Inactif' }}
                            </span>
                        </template>

                        <template v-else-if="column.key === 'id'">
                            {{ index + 1 }}
                        </template>

                        <template v-else-if="column.key === 'created_at'">
                            {{ formatDate(user.created_at) }}
                        </template>

                        <template v-else>
                            {{ user[column.key] }}
                        </template>
                    </td>

                    <td class="px-4 sm:px-6 py-3">
                        <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center justify-center">
                            <button @click="openModal(user)"
                                class="w-full sm:w-auto px-3 py-1 text-xs font-medium rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                                Info
                            </button>
                            <button @click="() => onDelete?.(user)"
                                class="w-full sm:w-auto flex items-center justify-center gap-1 px-3 py-1 text-xs font-medium rounded-md bg-red-100 text-red-800 hover:bg-red-200 transition">
                                <TrashIcon class="w-4 h-4" />
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="users.links" class="mt-6 p-4 flex flex-wrap justify-center gap-2 bg-white">
            <button v-for="link in users.links" :key="link.label" @click="() => goTopage(link.url)" v-html="link.label"
                :disabled="!link.url"
                class="min-w-[36px] text-xs sm:text-sm px-3 py-2 rounded-lg border font-semibold shadow-sm transition-all duration-200"
                :class="[
                    link.active
                        ? 'bg-indigo-600 text-white shadow-md'
                        : 'bg-white text-gray-700 hover:bg-gray-100',
                    !link.url && 'cursor-not-allowed text-gray-400 border-gray-300'
                ]" />
        </div>
    </div>

    <div v-if="selectedUser"
        class="fixed inset-0 bg-transparent backdrop-blur-[1px] bg-opacity-40 z-40 flex items-center justify-center px-4 py-8"
        @click.self="closeModal">
        <div class="relative bg-white rounded-3xl shadow-2xl p-6 w-full max-w-3xl space-y-6">
            <!-- Close Button -->
            <button @click="closeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">
                &times;
            </button>

            <!-- Header -->
            <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div class="relative w-24 h-24">
                    <img v-if="selectedUser?.info?.avatar" :src="`/${selectedUser.info.avatar}`" alt="Avatar"
                        class="w-full h-full rounded-full object-cover border-4 border-indigo-500 shadow-lg" />
                    <div v-else
                        class="w-full h-full rounded-full bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl font-bold shadow-md">
                        {{ (selectedUser.name && selectedUser.name.charAt(0).toUpperCase()) || '?' }}
                    </div>
                </div>

                <!-- Infos principales -->
                <div class="flex-1">
                    <h2 class="text-2xl font-extrabold text-gray-900">
                        {{ selectedUser.name || 'Nom inconnu' }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ selectedUser.email || 'Email non renseigné' }}</p>
                    <div class="mt-1">
                        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full"
                            :class="selectedUser.email_verified_at ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                            {{ selectedUser.email_verified_at ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <hr class="border-t border-gray-200" />

            <!-- User details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div>
                    <span class="font-medium">Créé le :</span>
                    <p>{{ formatDate(selectedUser.created_at) || 'Date inconnue' }}</p>
                </div>

                <div v-if="selectedUser.info?.birthdate">
                    <span class="font-medium">Date de naissance :</span>
                    <p>{{ selectedUser.info.birthdate }}</p>
                </div>

                <div v-if="selectedUser.info?.phone">
                    <span class="font-medium">Téléphone :</span>
                    <p>{{ selectedUser.info.phone }}</p>
                </div>

                <div v-if="selectedUser.info?.address">
                    <span class="font-medium">Adresse :</span>
                    <p>{{ selectedUser.info.address }}</p>
                </div>

                <div v-if="selectedUser.info?.bio" class="md:col-span-2">
                    <span class="font-medium">Bio :</span>
                    <p class="text-gray-600 italic">{{ selectedUser.info.bio }}</p>
                </div>
            </div>
        </div>
    </div>


</template>
