<script setup>
import TextLink from '@/components/TextLink.vue';
import { router } from '@inertiajs/vue3';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import Swal from 'sweetalert2';
import Authlayout from '@/components/app/owner/programme/Authlayout.vue';

const props = defineProps({
    data: Object,
});

// console.log(props.data)
function goBack() {
    router.visit(route('owner.home'), { replace: true });
}

function goTopage(url) {
    if (url) {
        router.visit(url);
    }
}

function deleteFormation(id) {
    Swal.fire({
        title: 'Confirmer la suppression',
        text: 'Voulez-vous vraiment supprimer ce témoignage ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('owner.formations.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Supprimé !', 'Le témoignage a été supprimé.', 'success');
                },
                onError: () => {
                    Swal.fire('Erreur !', "Une erreur s'est produite.", 'error');
                },
            });
        }
    });
}

function DraftFormation(slug) {
    Swal.fire({
        title: 'Mettre Non publier cette formation ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, non publier',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('owner.formation.draft', { slug }), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    // mise à jour directe du tableau
                    const index = props.data.formations.data.findIndex(f => f.slug === slug)
                    if (index !== -1) {
                        props.data.formations.data[index].status = 'draft'
                    }
                    Swal.fire({
                        title: 'Non Publiée !',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            })
        }
    })
}

function publishFormation(slug) {
    Swal.fire({
        title: 'Publier cette formation ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, publier',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('owner.formation.published', { slug }), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    // mise à jour directe du tableau
                    const index = props.data.formations.data.findIndex(f => f.slug === slug)
                    if (index !== -1) {
                        props.data.formations.data[index].status = 'published'
                    }
                    Swal.fire({
                        title: 'Publiée !',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            })
        }
    })
}
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div class="bg-gray-50 shadow-lg w-full mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
                        Gérer les formations
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Voici la liste de tous les formations pour votre école
                    </p>
                </div>

                <Authlayout :data="data"/>

                <div class="text-end mb-10">
                    <TextLink :href="route('owner.formation.add')"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition">
                        Ajouter
                    </TextLink>
                </div>

                <div class="head overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="thead bg-indigo-100">
                            <tr>
                                <th class="th">#</th>
                                <th class="th">Nom de la formation</th>
                                <th class="th">Description</th>
                                <th class="th">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm sm:text-base text-gray-800">
                            <tr v-for="(item, index) in data.formations.data" :key="item.id"
                                class="hover:bg-purple-50 transition duration-200">
                                <td class="td">{{ index + 1 }}</td>
                                <td class="tds">{{ item.title }}</td>
                                <td class="tds">{{ item.description }}</td>
                                <td class="td space-x-3">
                                    <TextLink :href="route('owner.formation.show', { slug: item.slug })"
                                        class="cursor-pointer px-3 py-1 rounded-md bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs font-medium transition">
                                        Détails
                                    </TextLink>
                                    <TextLink :href="route('owner.formation.edit', { slug: item.slug })"
                                        class="cursor-pointer px-3 py-1 rounded-md bg-green-100 text-green-800 hover:bg-green-200 text-xs font-medium transition">
                                        Modifier
                                    </TextLink>
                                    <button @click="publishFormation(item.slug)"
                                        class="cursor-pointer px-3 py-1 rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200 text-xs font-medium transition">
                                        Publier
                                    </button>
                                    <button @click="DraftFormation(item.slug)"
                                        class="cursor-pointer px-3 py-1 rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200 text-xs font-medium transition">
                                        Non publié
                                    </button>
                                    <button @click="deleteFormation(item.id)"
                                        class="cursor-pointer px-3 py-1 rounded-md bg-red-100 text-red-800 hover:bg-red-200 text-xs font-medium transition">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="props.data.formations.links" class="mt-8 flex flex-wrap justify-center gap-2">
                    <button v-for="link in props.data.formations.links" :key="link.label"
                        @click="() => goTopage(link.url)" v-html="link.label" :disabled="!link.url"
                        class="cursor-pointer min-w-[40px] rounded-xl border px-4 py-2 text-sm font-semibold shadow-sm transition-all duration-200"
                        :class="[
                            link.active
                                ? 'bg-indigo-600 text-white shadow-md'
                                : 'bg-white text-gray-700 hover:bg-gray-100',
                            !link.url && 'cursor-not-allowed text-gray-400 border-gray-300'
                        ]" />
                </div>
            </div>
        </div>

    </UserLayout>
</template>
