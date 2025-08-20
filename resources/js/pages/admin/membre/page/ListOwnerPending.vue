<script setup>
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';
import HeaderHautLayout from '@/components/app/HeaderHautLayout.vue';
import Authlayout from '@/components/app/admin/membre/Authlayout.vue';

const props = defineProps({
    users: Object,
    filters: Object,
    roleCounts: Object,
})

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Nom' },
    { key: 'email', label: 'Email' },
    { key: 'active', label: 'Statut Email' },
    { key: 'created_at', label: 'Créer Le' },
]

function handleDelete(user) {
    Swal.fire({
        title: `Supprimer ${user.name} ?`,
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.membre.destroy', user.id), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Supprimé !',
                        text: 'L’utilisateur a été supprimé avec succès.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                },
                onError: () => {
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'La suppression a échoué.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            })
        }
    })
}

</script>

<template>
    <HeaderHautLayout>
        <Authlayout :columns="columns" :users="props.users"
            :rout="{ name: 'admin.membre', params: { role: 'pending_owner' } }" :on-delete="handleDelete"
            :roleCounts="roleCounts" />
    </HeaderHautLayout>

</template>
