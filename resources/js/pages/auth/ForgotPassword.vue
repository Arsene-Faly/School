<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout title="Mot de passe oublié">
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="email" class="text-md sm:text-lg label">Adresse email</Label>
                    <Input id="email" type="email" name="email" autocomplete="off" v-model="form.email" autofocus
                        placeholder="email@example.com"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="btn"
                        :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Envoyer le lien de réinitialisation du mot de passe
                    </Button>
                </div>
            </form>

            <div class="text-center text-md sm:text-lg text-gray-600 mt-5">
                <span>Ou, Revenir dans </span>
                <TextLink :href="route('login')" class="text-indigo-500 hover:text-indigo-700">se connecter</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
