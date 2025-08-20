<script setup>
import InputError from '@/components/InputError.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    data: Object
})

const form = useForm({
    user_id: props.data.user,
    school_id: props.data.school.id,
    content: ''
})

// console.log(props.data.school.id)


function submit() {
    form.post(route('etablissement.store.testimonial'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'Avis enregistrée avec succès !',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            });

            form.content = ''
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue, veuillez réessayer.',
            });
        },
    });
}
function goBack() {
    window.history.back()
}
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div
                class="bg-gray-50 shadow-lg rounded-2xl w-full max-w-7xl mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
                        Ajouter une Avis
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Veuillez remplir les champs ci-dessous pour mettre à jour les informations de votre école.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">
                    <!-- Description -->
                    <div>
                        <Label for="description" class="text-indigo-600 text-lg font-semibold">Description</Label>
                        <textarea id="description" v-model="form.content" placeholder="Contenu" rows="4"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                        :disabled="form.processing">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </UserLayout>
</template>
