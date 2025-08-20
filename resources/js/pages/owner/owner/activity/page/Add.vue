<script setup>
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  schoolId: Number,
})

const form = useForm({
    school_id: props.schoolId,
    title: '',
    description: '',
    cover_photo: null,
});

const previewCover = ref(null);

function onCoverPhotoChange(event) {
    const file = event.target.files?.[0] ?? null;
    form.cover_photo = file;

    if (previewCover.value && previewCover.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewCover.value);
    }
    previewCover.value = file ? URL.createObjectURL(file) : null;
}

function goBack() {
    router.visit(route('owner.activity.status', { status: 'draft' }), { replace: true });
}

function submit() {
    form.post(route('owner.activity.store'), {
        onSuccess: () => {
            form.reset(),
            previewCover.value = null;
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'Formation enregistrée avec succès !',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
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

onBeforeUnmount(() => {
    if (previewCover.value && previewCover.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewCover.value);
    }
});
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
                        Ajouter des activitées
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Voici la liste de tous les activitées pour votre école
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">
                    <div>
                        <Label for="cover_photo" class="text-indigo-600 text-lg font-semibold">Photo de
                            couverture</Label>
                        <Input id="cover_photo" type="file" @change="onCoverPhotoChange" accept="image/*"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <div class="mt-3">
                            <img v-if="previewCover" :src="previewCover" alt="Aperçu cover"
                                class="w-full max-h-48 object-cover rounded-md border" />
                        </div>
                    </div>
                    <div>
                        <Label for="name" class="text-sm sm:text-base label">Nom de la formation <span
                                class="text-red-500">*</span></Label>
                        <Input id="name" v-model="form.title" type="text" required autocomplete="title"
                            placeholder="ex: Dev" class="input mt-2 text-sm sm:text-base" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div>
                        <Label for="description" class="text-indigo-600 text-lg font-semibold">Description</Label>
                        <textarea id="description" v-model="form.description" placeholder="Description" rows="4"
                            class="bg-white mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                    </div>

                    <div class="pt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="cursor-pointer w-full md:w-1/3 flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2 text-white font-semibold text-base sm:text-lg transition duration-200 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                            <span>{{ form.processing ? 'Veuillez patienter...' : 'Ajouter' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>

</template>
