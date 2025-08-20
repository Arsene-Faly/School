<script setup lang="ts">
import { usePage, useForm } from '@inertiajs/vue3'
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { LoaderCircle } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Swal from 'sweetalert2'
import DeleteAccount from './DeleteAccount.vue';

interface Props {
    status?: string;
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profileNew.update'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Profil mis à jour !',
                text: 'Vos informations ont été enregistrées avec succès.',
                confirmButtonColor: '#4f46e5', // couleur indigo
                confirmButtonText: 'OK'
            })
        },
    });
};

import TitleLayout from './Layout/TitleLayout.vue';
import AuthProfile from './AuthProfile.vue';
</script>

<template>
    <AuthProfile>
        <div class="w-full mb-[5rem]">
            <TitleLayout title="Informations sur votre Profile">

            </TitleLayout>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div class="space-y-2 max-w-xl">
                        <Label for="name" class="text-indigo-600 text-lg font-semibold">Nom complet <span
                                class="text-red-500">*</span></Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                            v-model="form.name" placeholder="ex: Ratsimbason Faly"
                            class="block border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <InputError :message="form.errors.name" />

                        <Label for="email" class="text-indigo-600 text-lg font-semibold">Adresse email <span
                                class="text-red-500">*</span></Label>
                        <Input id="email" type="email" readonly required autofocus :tabindex="1" autocomplete="email"
                            v-model="form.email" placeholder="ex: contact@monavenir.edu"
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <Button type="submit" :tabindex="4" :disabled="form.processing"
                        class="cursor-pointer mt-6 w-[20%] flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-lg font-semibold text-white transition-all duration-200 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                        <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                    </Button>
                </div>
            </form>
        </div>

        <div>
            <TitleLayout title="Supprimer Votre compte">
            </TitleLayout>

            <DeleteAccount />
        </div>
    </AuthProfile>
</template>
