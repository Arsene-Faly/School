<script setup lang="ts">
import { usePage, useForm } from '@inertiajs/vue3'
import { ref } from 'vue';
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';

// Import SweetAlert2
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import AuthProfile from './page/AuthProfile.vue';
import Section from '@/components/app/user/section/Section.vue';
import TitleLayout from '@/components/app/TitleLayout.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';

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
    <UserLayout>
        <SectionDefault title="Page De Profile">
        </SectionDefault>

        <Section name="Profiles Informations">
            <AuthProfile>
                <div class="max-w-7xl mx-auto rounded-3xl p-0 sm:px-10 py-14 md:px-16 flex flex-col gap-10 items-center">
                    <div class="">
                        <TitleLayout title="🔒 Modifier votre mot de passe" />
                        <p class="text-gray-500 text-md mt-2">Pour garantir la sécurité de votre compte, choisissez un
                            mot de passe fort.</p>
                    </div>

                    <form @submit.prevent="updatePassword" class="w-full max-w-2xl space-y-8">
                        <div>
                            <Label for="current_password" class="block text-sm font-semibold text-indigo-600 mb-2">Mot
                                de passe actuel<span class="text-red-500">*</span></Label>
                            <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                                type="password"
                                class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                autocomplete="current-password" placeholder="Mot de passe actuel" />
                            <InputError :message="form.errors.current_password" class="mt-1" />
                        </div>

                        <div>
                            <Label for="password" class="block text-sm font-semibold text-indigo-600 mb-2">Nouveau mot
                                de passe<span class="text-red-500">*</span></Label>
                            <Input id="password" ref="passwordInput" v-model="form.password" type="password"
                                class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                autocomplete="new-password" placeholder="Nouveau mot de passe" />
                            <InputError :message="form.errors.password" class="mt-1" />
                        </div>

                        <div>
                            <Label for="password_confirmation"
                                class="block text-sm font-semibold text-indigo-600 mb-2">Confirmation mot de passe<span
                                    class="text-red-500">*</span></Label>
                            <Input id="password_confirmation" v-model="form.password_confirmation" type="password"
                                class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                autocomplete="new-password" placeholder="Confirmation mot de passe" />
                            <InputError :message="form.errors.password_confirmation" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-between">
                            <Button :disabled="form.processing"
                                class="w-full md:w-auto rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold text-base hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Enregistrer le mot de passe
                            </Button>

                            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                <p v-show="form.recentlySuccessful" class="text-sm text-green-600 font-medium ml-4">✔
                                    Mot de passe modifié</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </AuthProfile>
        </Section>
    </UserLayout>
</template>
