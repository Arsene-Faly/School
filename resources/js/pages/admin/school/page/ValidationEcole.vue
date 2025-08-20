<script setup>
import { ref, watch } from 'vue';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';
import Input from '@/components/ui/input/Input.vue';
import { debounce } from 'lodash-es'
import Authlayout from '@/components/app/admin/school/Authlayout.vue';

const props = defineProps({
    ecoles: Object,
    filters: Object,
    count: Object
});

const isImage = (filename) => {
    return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(filename);
};

const isPDF = (filename) => {
    return /\.pdf$/i.test(filename);
};

function goTopage(url) {
    if (url) {
        router.visit(url);
    }
}

const query = ref('')

const searchUsers = () => {
    router.get('/admin/ecole', { search: query.value }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const debouncedSearch = debounce(searchUsers, 500)

watch(query, () => {
    debouncedSearch()
})

const selectedEcole = ref(null)
const showModal = ref(false)

const openModal = (ecole) => {
    selectedEcole.value = ecole
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    selectedEcole.value = null
}

const formatDate = (dateStr) => {
    const date = new Date(dateStr)
    return date.toLocaleString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const deleteEcole = (id) => {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action supprimera définitivement l’école.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.ecole.delete', id), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Supprimé !',
                        text: 'L’école a été supprimée avec succès.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                },
                onError: () => {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Une erreur est survenue lors de la suppression.',
                        icon: 'error',
                    })
                }
            })
        }
    })
}

const validerEcole = (ecole) => {
    Swal.fire({
        title: 'Valider cette école ?',
        text: `Cela activera l'école "${ecole.nom}" et le compte owner.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, valider',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('ecoles.valider', ecole.id), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Validée !',
                        text: `L'école "${ecole.nom}" a été validée.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                },
                onError: () => {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Une erreur est survenue.',
                        icon: 'error',
                    })
                }
            })
        }
    })
}
</script>

<template>
    <div class="p-4 mx-3 mb-8 bg-white shadow-md rounded-xl">
        <Authlayout :count="count">
        </Authlayout>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 px-4">
            <div class="flex-1">
                <label for="search" class="block mb-1 text-sm font-medium text-gray-700">Recherche</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.15 6.15z" />
                    </svg>
                    <Input id="search" v-model="query" @keyup.enter="searchUsers" type="text"
                        placeholder="Recherche par nom ou email"
                        class="w-full pl-10 pr-4 py-3 text-base text-gray-800 placeholder-gray-400 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </div>
        </div>
        <div class="head">
            <table class="min-w-full table-auto">
                <thead class="thead">
                    <tr>
                        <th class="th">#</th>
                        <th class="th">Nom de l’école</th>
                        <th class="th">Propriétaire</th>
                        <th class="th">Email du propriétaire</th>
                        <th class="th">Date d’inscription</th>
                        <th class="th">Actions</th>
                    </tr>
                </thead>

                <tbody class="tbody">
                    <tr v-for="(item, index) in props.ecoles.data" :key="item.id" class="hover:bg-indigo-50 transition">
                        <td class="td">{{ index + 1 }}</td>
                        <td class="td">{{ item.name }}</td>
                        <td class="td">{{ item.user.name }}</td>
                        <td class="td">{{ item.user.email }}</td>
                        <td class="td">{{ formatDate(item.user.created_at) }}</td>
                        <td class="td">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button @click="openModal(item)"
                                    class="px-3 py-1 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 text-xs font-medium transition">
                                    Info
                                </button>
                                <button v-if="item.status === 'pending'" @click="validerEcole(item)"
                                    class="px-3 py-1 rounded-md bg-green-100 text-green-800 hover:bg-green-200 text-xs font-medium transition">
                                    Valider
                                </button>
                                <button @click="deleteEcole(item.id)"
                                    class="px-3 py-1 rounded-md bg-red-100 text-red-800 hover:bg-red-200 text-xs font-medium transition">
                                    Rejeter
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="ecoles.links" class="mt-6 flex flex-wrap justify-center gap-2 px-4 py-3">
                <button v-for="link in ecoles.links" :key="link.label" @click="() => goTopage(link.url)"
                    v-html="link.label" :disabled="!link.url"
                    class="min-w-[40px] rounded-md border px-3 py-2 text-xs sm:text-sm font-medium transition duration-200"
                    :class="[
                        link.active ? 'bg-indigo-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-gray-100',
                        !link.url && 'cursor-not-allowed text-gray-400 border-gray-300'
                    ]" />
            </div>
        </div>
    </div>


    <template v-if="showModal">
        <div class="fixed inset-0 bg-transparent backdrop-blur-[1px] bg-opacity-40 z-40 flex items-center justify-center px-4 py-8"
            @click.self="closeModal">
            <div class="bg-gray-100 w-full max-w-7xl rounded-3xl shadow-2xl p-8 relative animate-fade-in">
                <h2
                    class="text-2xl md:text-3xl font-extrabold tracking-wide text-indigo-700 uppercase mb-8 text-center">
                    Détails de l’école
                </h2>

                <div class="flex flex-wrap gap-12">
                    <!-- Section École -->
                    <section class="flex-1 min-w-[280px]">
                        <h3 class="text-lg md:text-xl font-semibold text-gray-900 border-b pb-2 mb-4">
                            Informations sur l’école
                        </h3>
                        <div class="space-y-2 text-sm sm:text-base text-gray-700">
                            <p><span class="font-medium">Nom :</span> {{ selectedEcole.name }}</p>
                            <p><span class="font-medium">NIF :</span> {{ selectedEcole.nif }}</p>
                            <p><span class="font-medium">STAT :</span> {{ selectedEcole.stat }}</p>
                            <p><span class="font-medium">Adresse :</span> {{ selectedEcole.adresse }}</p>
                            <p><span class="font-medium">Contact :</span> {{ selectedEcole.contact }}</p>
                        </div>
                    </section>

                    <!-- Section Document -->
                    <section class="flex-1 min-w-[280px]">
                        <h3 class="text-lg md:text-xl font-semibold text-gray-900 border-b pb-2 mb-4">
                            Document fourni
                        </h3>

                        <div v-if="selectedEcole.document" class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                            <div v-if="isImage(selectedEcole.document)">
                                <img :src="`/${selectedEcole.document}`" alt="Document image"
                                    class="max-w-full h-auto rounded shadow-md" />
                            </div>

                            <div v-else>
                                <a :href="`/${selectedEcole.document}`" target="_blank" rel="noopener"
                                    class="text-indigo-600 underline hover:text-indigo-800 font-medium">
                                    Télecharger le document
                                </a>
                            </div>
                        </div>

                        <div v-else class="text-gray-500 italic">Aucun document fourni.</div>
                    </section>


                    <!-- Section Propriétaire -->
                    <section class="flex-1 min-w-[280px]">
                        <h3 class="text-lg md:text-xl font-semibold text-gray-900 border-b pb-2 mb-4">
                            Informations sur le propriétaire
                        </h3>
                        <div class="space-y-2 text-sm sm:text-base text-gray-700">
                            <p><span class="font-medium">Nom :</span> {{ selectedEcole.user.name }}</p>
                            <p><span class="font-medium">Email :</span> {{ selectedEcole.user.email }}</p>
                        </div>
                    </section>
                </div>

                <!-- Bouton fermeture -->
                <div class="text-right mt-8">
                    <button @click="closeModal"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition font-semibold text-sm sm:text-base">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </template>




</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
