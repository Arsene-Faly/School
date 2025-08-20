<script setup>
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import TextLink from '@/components/TextLink.vue';
import Button from '@/components/ui/button/Button.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { Link } from '@inertiajs/vue3'  // si tu utilises Inertia
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    favorites: Array
})

console.log(props.favorites)

function deleteFavoris(favId) {
    Swal.fire({
        title: 'Confirmer la suppression',
        text: 'Voulez-vous vraiment supprimer ce Favoris ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('favorite.destroy', { favoris: favId }), {
                preserveScroll: true,
                preserveState: true, // <-- important
                onSuccess: () => {
                    // Supprimer le favori côté Vue
                    favoritesList.value = favoritesList.value.filter(f => f.pivot.id !== favId);

                    Swal.fire('Supprimé !', 'Le Favoris a été supprimé.', 'success');
                },
                onError: () => {
                    Swal.fire('Erreur !', "Une erreur s'est produite.", 'error');
                },
            });
        }
    });
}


</script>

<template>
    <UserLayout>
        <SectionDefault :title="`Page de favoris`">
        </SectionDefault>

        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6 text-indigo-700">⭐ Mes Favoris</h1>

            <div v-if="favorites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="school in favorites" :key="school.id"
                    class="p-4 bg-white shadow-lg rounded-2xl border border-indigo-100 hover:shadow-xl transition flex flex-col justify-between">
                    <!-- Logo et infos -->
                    <div>
                        <div class="flex items-center space-x-4">
                            <img v-if="school.info.logo" :src="school.info.logo" alt="Logo école"
                                class="w-16 h-16 object-contain rounded-full border border-indigo-200" />
                            <div>
                                <h2 class="text-lg font-semibold text-indigo-800">{{ school.name }}</h2>
                                <p class="text-gray-600">{{ school.adresse }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="text-sm text-gray-500">📞 Contact : {{ school.contact }}</p>
                        </div>
                    </div>

                    <!-- Lien détails -->
                    <div class="mt-4 text-right space-x-2.5">
                        <TextLink :href="route('etablissement.show', { slug: school.slug })"
                            class="inline-block px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 transition">
                            Voir détails
                        </TextLink>
                        <Button @click="deleteFavoris(school.pivot.id)"
                            class="inline-block px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg shadow hover:bg-red-700 transition">
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-10">
                <p class="text-gray-500">Aucune école ajoutée en favoris.</p>
            </div>
        </div>
    </UserLayout>
</template>
