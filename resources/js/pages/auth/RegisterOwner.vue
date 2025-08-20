<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'pending_owner',
});

const submit = () => {
    form.post(route('register.owner'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Créer un compte propriétaire d'école">

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="name" class="text-md sm:text-lg label">Nom Complet</Label>
                    <Input id="name" type="text" required autofocus autocomplete="name" v-model="form.name"
                        placeholder="Entrer votre nom complet" class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-md sm:text-lg label">Adresse email</Label>
                    <Input id="email" type="email" required autocomplete="email" v-model="form.email"
                        placeholder="email@example.com" class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-md sm:text-lg label">Mot de passe</Label>
                    <Input id="password" type="password" required autocomplete="new-password" v-model="form.password"
                        placeholder="Mot de passe" class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        class="text-indigo-600 text-md sm:text-lgfont-semibold">Confirmation mot de passe</Label>
                    <Input id="password_confirmation" type="password" required autocomplete="new-password"
                        v-model="form.password_confirmation" placeholder="Confirmer mot de passe"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="btn" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Créer un compte Owner
                </Button>
            </div>

            <div class="text-center text-sm sm:text-lg text-gray-600">
                Déjà un compte ?
                <TextLink :href="route('login')" class="text-indigo-500 hover:text-indigo-700">Se connecter</TextLink>
            </div>

            <!-- 🔵 Lien pour les propriétaires -->
            <div class="text-center text-sm sm:text-lg text-gray-600 mt-0">
                Vous n'êtes pas un prorietaire ?
                <TextLink :href="route('register')" class="text-indigo-600 font-semibold hover:underline">
                    S’inscrire comme utilisateur
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>
