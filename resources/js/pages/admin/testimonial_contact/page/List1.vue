<script setup>
import Authlayout from '@/components/app/admin/testimonial/Authlayout.vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    data: Object,
});

function goTopage(url) {
    if (url) {
        router.visit(url);
    }
}

function deleteContact(id) {
    Swal.fire({
        title: 'Confirmer la suppression',
        text: 'Voulez-vous vraiment supprimer ce Contact ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.contact.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire('Supprimé !', 'Le Contact a été supprimé.', 'success');
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
    <div class="p-4 mx-3 mb-8 bg-white shadow-md rounded-xl">
        <Authlayout :data="data" />

        <div class="head">
            <table class="min-w-full table-auto">
                <thead class="thead">
                    <tr>
                        <th class="th">#</th>
                        <th class="th">Nom de l'utilisateur</th>
                        <th class="th">Message</th>
                        <th class="th">Actions</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <tr v-for="(item, index) in props.data.contacts.data" :key="item.id"
                        class="hover:bg-purple-50 transition duration-200">
                        <td class="td">{{ index + 1 }}</td>
                        <td class="td">{{ item.user.name }}</td>
                        <td class="tds">{{ item.message }}</td>
                        <td class="td flex gap-2">
                            <button @click="deleteContact(item.id)"
                                class="cursor-pointer px-3 py-1 rounded-md bg-red-100 text-red-800 hover:bg-red-200 text-xs font-medium transition">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="props.data.contacts.links" class="mt-8 flex flex-wrap justify-center gap-2">
                <button v-for="link in props.data.contacts.links" :key="link.label" @click="() => goTopage(link.url)"
                    v-html="link.label" :disabled="!link.url"
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
</template>
