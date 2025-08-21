<script setup>
import InputError from '@/components/InputError.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const page = usePage();
const user = page.props.auth.user ?? '';

const form = useForm({
    name: user.name,
    description: '',
});


const submitting = ref(false);

const submit = () => {
    if (submitting.value) return; // bloque si déjà en cours

    submitting.value = true;

    form.post(route('testimonial.add'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Merci pour votre témoignage !',
                text: 'Votre message a été envoyé avec succès.',
                confirmButtonColor: '#6366F1', // indigo
            });

            form.reset('description');

            const el = document.getElementById('testimonials');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        },
        onFinish: () => {
            submitting.value = false; // réactive le bouton
        },
    });
};

</script>


<template>
    <div class="max-w-2xl mx-auto mt-10 bg-gray-100 p-8 rounded-2xl shadow-xl border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-center text-indigo-700">
            Laissez votre témoignage
        </h2>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Nom -->
            <div>
                 <label class="block text-sm text-indigo-700 pb-2 font-semibold" for="name">
                    Nom<span class="text-red-500">*</span>
                </label>
                <input readonly v-model="form.name" type="text"
                    class="w-full px-4 py-2 rounded-md bg-white text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Votre nom" />
            </div>

            <!-- Témoignage -->
            <div>
                <label class="block text-sm text-indigo-700 pb-2 font-semibold" for="name">
                    Témoignage <span class="text-red-500">*</span>
                </label>
                <textarea v-model="form.description" rows="4"
                    class="w-full px-4 py-2 rounded-md bg-white text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Votre message..."></textarea>
                <InputError :message="form.errors.description" />
            </div>

            <!-- Bouton -->
            <div class="text-center">
                <button type="submit"
                    :disabled="submitting"
                    class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl shadow-sm transition duration-300">
                    Envoyer le témoignage
                </button>
            </div>
        </form>
    </div>

</template>
