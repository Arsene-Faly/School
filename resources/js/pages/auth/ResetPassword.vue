<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

interface Props {
    token: string;
    email: string;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthLayout title="Reset password" description="Please enter your new password below">

        <Head title="Reset password" />

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="text-md sm:text-lg label">Adresse email</Label>
                    <Input id="email" type="email" name="email" autocomplete="email" v-model="form.email"
                        class="text-md sm:text-lg input"
                        readonly />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-md sm:text-lg label">Password</Label>
                    <Input id="password" type="password" name="password" autocomplete="new-password"
                        v-model="form.password"
                        class="text-md sm:text-lg input"
                        autofocus placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-md sm:text-lg label"> Confirmation mot de passe </Label>
                    <Input id="password_confirmation" type="password" name="password_confirmation"
                        autocomplete="new-password" v-model="form.password_confirmation"
                        class="text-md sm:text-lg input"
                        placeholder="Confirm password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit"
                                class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white text-md sm:text-lg py-5 cursor-pointer"

                :disabled="form.processing">
                    <LoaderCircle v-if="form.processing"
                    class="h-4 w-4 animate-spin"
                    />
                    Reset password
                </Button>
            </div>
        </form>
    </AuthLayout>
</template>
