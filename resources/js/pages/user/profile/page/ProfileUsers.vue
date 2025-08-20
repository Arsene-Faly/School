<script setup lang="ts">
import { usePage, useForm } from '@inertiajs/vue3'
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';
import { LoaderCircle } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Swal from 'sweetalert2'
import Section from '@/components/app/user/section/Section.vue';
import TitleLayout from '@/components/app/TitleLayout.vue';
import AuthProfile from './AuthProfile.vue';
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
    form.patch(route('profile.user.update'), {
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

</script>

<template>
    <Section name="Profiles Informations">
        <AuthProfile>
            <div class="mx-auto mb-20 rounded-3xl p-0 sm:px-10 py-14 md:px-16 flex flex-col gap-16">

                <div>
                    <TitleLayout title="Informations sur votre profil" />
                    <p class="text-gray-500 text-md mt-2 mb-6">Mettez à jour votre nom complet et votre adresse email.
                    </p>

                    <form @submit.prevent="submit" class="space-y-8">
                        <div>
                            <Label for="name" class="text-md sm:text-lg label">
                                Nom complet <span class="text-red-500">*</span>
                            </Label>
                            <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                                v-model="form.name" placeholder="ex: Ratsimbason Faly"
                                class="text-md sm:text-lg input" />
                            <InputError :message="form.errors.name" class="mt-1" />
                        </div>

                        <div>
                            <Label for="email" class="text-md sm:text-lg label">
                                Adresse email <span class="text-red-500">*</span>
                            </Label>
                            <Input id="email" readonly type="email" required :tabindex="2" autocomplete="email"
                                v-model="form.email" placeholder="ex: contact@monavenir.edu"
                                class="text-md sm:text-lg input" />
                        </div>

                        <div class="flex justify-end">
                            <Button type="submit" :tabindex="4" :disabled="form.processing"
                                class="flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold text-base hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                                <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                            </Button>
                        </div>
                    </form>
                </div>

                <div class="pt-10 border-t border-gray-200">
                    <TitleLayout title="Supprimer votre compte" />
                    <p class="text-gray-500 text-sm mt-2 mb-6">Cette action est irréversible. Veuillez confirmer avant
                        de continuer.</p>
                    <DeleteAccount />
                </div>

            </div>
        </AuthProfile>
    </Section>
</template>
