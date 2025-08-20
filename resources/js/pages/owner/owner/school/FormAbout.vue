<script setup>
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Label from '@/components/ui/label/Label.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Swal from 'sweetalert2';

const props = defineProps({
    schools: Object
});

const form = useForm({
    description: props.schools.description || '',
    mission: props.schools.mission || '',
    vision: props.schools.vision || '',
    history: props.schools.history || ''
});

function goBack() {
    window.history.back();
}

const submit = () => {
    form.post(route('owner.aboutUpdate.school'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Info École mise à jour !',
                text: 'Vos informations ont été enregistrées avec succès.',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            });
        },
    });
};
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div class="bg-gray-50 shadow-lg rounded-2xl w-full max-w-7xl mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <!-- Bouton retour -->
                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <!-- Titre -->
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
                        Modifier les informations de l’établissement
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Veuillez remplir les champs ci-dessous pour mettre à jour les informations de votre école.
                    </p>
                </div>

                <!-- Formulaire -->
                <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">

                    <!-- Mission -->
                    <div>
                        <Label for="mission" class="text-sm sm:text-base label">Votre mission <span class="text-red-500">*</span></Label>
                        <textarea id="mission" v-model="form.mission" required
                            placeholder="Entrez la mission..."
                            class="input mt-2 text-sm sm:text-base h-24 resize-none"></textarea>
                        <InputError :message="form.errors.mission" />
                    </div>

                    <!-- Vision -->
                    <div>
                        <Label for="vision" class="text-sm sm:text-base label">Votre vision <span class="text-red-500">*</span></Label>
                        <textarea id="vision" v-model="form.vision" required
                            placeholder="Entrez la vision..."
                            class="input mt-2 text-sm sm:text-base h-24 resize-none"></textarea>
                        <InputError :message="form.errors.vision" />
                    </div>

                    <!-- Description -->
                    <div>
                        <Label for="description" class="text-sm sm:text-base label">Longue description <span class="text-red-500">*</span></Label>
                        <textarea id="description" v-model="form.description" required
                            placeholder="Entrez une longue description..."
                            class="input mt-2 text-sm sm:text-base h-32 resize-none"></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Histoire -->
                    <div>
                        <Label for="history" class="text-sm sm:text-base label">Histoire de l'école</Label>
                        <textarea id="history" v-model="form.history"
                            placeholder="Entrez l'histoire..."
                            class="input mt-2 text-sm sm:text-base h-32 resize-none"></textarea>
                        <InputError :message="form.errors.history" />
                    </div>

                    <!-- Bouton -->
                    <div class="pt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="cursor-pointer w-full md:w-1/3 flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold text-base sm:text-lg transition duration-200 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                            <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>
