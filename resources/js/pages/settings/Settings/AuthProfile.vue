<script setup>
import TextLink from '@/components/TextLink.vue';

const current = route().current()
console.log('Route actuelle :', current)

</script>

<template>
    <div class="mx-auto px-6 py-8 my-6 space-y-10 bg-gray-50 shadow-sm rounded-2xl">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Paramètres</h1>
            <p class="mt-1 text-base text-gray-500">Mettez à jour les informations de votre profil.</p>
        </div>

        <nav class="flex flex-col md:flex-row md:justify-center md:space-x-8 space-y-4 md:space-y-0 border-b border-gray-200 text-base font-medium overflow-x-auto no-scrollbar">
            <TextLink :href="route('profileNew.edit')"
                :class="['pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
                    current === 'profileNew.edit' ? 'border-indigo-600 text-indigo-600 border-b-2' : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-500']">
                Profil
            </TextLink>
            <TextLink :href="route('profileNewDetail.edit')"
                :class="['pb-3 transition-all duration-200 ease-in-out whitespace-nowrap ',
                    current === 'profileNewDetail.edit' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-500']">
                Plus de détails
            </TextLink>
            <TextLink :href="route('passwordNew.edit')"
                :class="['pb-3 transition-all duration-200 ease-in-out whitespace-nowrap ',
                    current === 'passwordNew.edit' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-500']">
                Mot de passe
            </TextLink>
            <TextLink :href="route('profileNew.show') + '#profile'" :class="[
                'pb-3 transition-all duration-200 ease-in-out whitespace-nowrap',
                current === 'profileNew.show'
                    ? 'border-b-2 border-indigo-600 text-indigo-600'
                    : 'text-gray-500 hover:text-indigo-600 hover:border-indigo-400'
            ]">
                Voir votre Profil
            </TextLink>
        </nav>

        <slot />

    </div>
</template>
