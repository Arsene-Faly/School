<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            form.email = '';
        },
    });
};
</script>

<template>
    <AuthBase title="Connexion à votre compte">

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div class="space-y-2">
                    <Label for="email" class="text-md sm:text-lg label">Adresse email</Label>
                    <Input id="email" type="email" required autofocus :tabindex="1" autocomplete="email"
                        v-model="form.email" placeholder="ex: contact@monavenir.edu"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-md sm:text-lg label">Mot de
                            passe</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')"
                            class="text-sm sm:text-md label" :tabindex="5">
                            Mot de passe oublié ?
                        </TextLink>
                    </div>
                    <Input id="password" type="password" required :tabindex="2" autocomplete="current-password"
                        v-model="form.password" placeholder="••••••••"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center space-x-3 cursor-pointer">
                    <Checkbox id="remember" v-model="form.remember" :tabindex="3" class="cursor-pointer" />
                    <Label for="remember" class="text-gray-700 sm:py-6 cursor-pointer">Se souvenir de moi</Label>
                </div>

                <Button type="submit"
                    class="btn"
                    :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                    Se connecter
                </Button>
            </div>

            <div class="text-center text-sm sm:text-lg text-gray-600 mt-5">
                Vous n'avez pas de compte ?
                <TextLink :href="route('register')" class="text-indigo-500 hover:text-indigo-700" :tabindex="5">
                    Créez-en un compte
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>
