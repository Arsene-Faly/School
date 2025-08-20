<script setup lang="ts">
import { usePage, useForm } from '@inertiajs/vue3'
import TitleLayout from './Layout/TitleLayout.vue';
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';

// Import SweetAlert2
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import AuthProfile from './AuthProfile.vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            // Afficher alerte succès
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'Votre mot de passe a été mis à jour avec succès.',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        },
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }

            // Afficher une alerte erreur générale si besoin (optionnel)
            if (Object.keys(errors).length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Merci de corriger les erreurs dans le formulaire.',
                });
            }
        },
    });
};
</script>


<template>
    <AuthProfile>
        <TitleLayout title="Modifier Votre mot de passe">
        </TitleLayout>

        <form @submit.prevent="updatePassword" class="space-y-6 max-w-xl">
            <div class="grid gap-2">
                <Label for="current_password" class="text-indigo-600 text-lg font-semibold">Mot de passe actuel<span
                        class="text-red-500">*</span></Label>
                <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password" type="password"
                    class="block border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    autocomplete="current-password" placeholder="Mot de passe actuel"/>
                <InputError :message="form.errors.current_password" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-indigo-600 text-lg font-semibold">Nouveau mot de passe<span
                        class="text-red-500">*</span></Label>
                <Input id="password" ref="passwordInput" v-model="form.password" type="password"
                    class="block border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    autocomplete="new-password" placeholder="Nouveau mot de passe" />
                <InputError :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation" class="text-indigo-600 text-lg font-semibold">Confirmation mot de
                    passe<span class="text-red-500">*</span></Label>
                <Input id="password_confirmation" v-model="form.password_confirmation" type="password"
                    class="block border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    autocomplete="new-password" placeholder="Confirmation mot de passe" />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing"
                    class="cursor-pointer mt-2 w-[40%] flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-lg font-semibold text-white transition-all duration-200 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Save
                    password</Button>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </AuthProfile>
</template>
