<script setup>

import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const page = usePage();
const user = page.props.auth.user ?? '';

const form = useForm({
    name: user.name,
    email: user.email,
    message: '',
});

const submit = () => {
    form.post(route('contact.add'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Merci pour votre Contact !',
                text: 'Votre message a été envoyé avec succès.',
                confirmButtonColor: '#6366F1', // indigo
            });

            form.message = '';

            const el = document.getElementById('contacts');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
};
</script>

<template>
    <UserLayout>
        <SectionDefault title="Page De Contact">
        </SectionDefault>

        <Section id="contacts" name="Contactez-nous"
            description="Notre plateforme vous permet d'explorer les écoles les mieux notées.">

            <div class="w-full px-0 md:px-8 lg:px-16 xl:px-32 2xl:px-40 py-10 mx-auto">
                <div class="flex flex-col md:flex-row gap-10">
                    <div class="w-full md:w-1/2">
                        <form class="bg-gray-100 rounded-xl shadow-lg p-6 md:p-8 space-y-6" @submit.prevent="submit">
                            <h2 class="text-2xl font-bold text-indigo-800 mb-2 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 12a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm2 0a6 6 0 1 1-12 0 6 6 0 0 1 12 0zm-6 6v2m0 0h6m-6 0H6" />
                                </svg>
                                <span>Envoyez-nous un message</span>
                            </h2>

                            <fieldset class="mb-4">
                                <label class="block text-sm text-indigo-700 pb-2 font-semibold" for="name">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" readonly v-model="form.name"
                                    class="block w-full border border-gray-300 bg-white rounded py-2 px-3 text-sm text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    type="text" placeholder="Votre nom" required />
                            </fieldset>

                            <fieldset class="mb-4">
                                <label class="block text-sm text-indigo-700 pb-2 font-semibold" for="email">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" readonly v-model="form.email"
                                    class="block w-full border border-gray-300 bg-white rounded py-2 px-3 text-sm text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    type="email" placeholder="votre@email.com" required />
                            </fieldset>

                            <fieldset class="mb-4">
                                <label class="block text-sm text-indigo-700 pb-2 font-semibold" for="message">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message" name="message" v-model="form.message"
                                    class="block w-full border border-gray-300 bg-white rounded py-2 px-3 text-sm text-gray-800 placeholder-gray-500 h-32 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                                    placeholder="Votre message..." required></textarea>
                                <InputError :message="form.errors.message" />
                            </fieldset>

                            <button v-if="user" type="submit"
                                class="cursor-pointer w-full flex items-center justify-center gap-2 text-base py-2 px-4 bg-indigo-600 hover:bg-indigo-700 transition text-white rounded-lg font-semibold shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Envoyer
                            </button>
                            <TextLink :href="route('login')" v-else type="submit"
                                class="cursor-pointer w-full flex items-center justify-center gap-2 text-base py-2 px-4 bg-indigo-600 hover:bg-indigo-700 transition text-white rounded-lg font-semibold shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Envoyer
                            </TextLink>
                        </form>
                    </div>

                    <!-- Coordonnées et carte -->
                    <div class="w-full md:w-1/2 flex flex-col gap-6">
                        <div class="overflow-hidden rounded-xl border border-gray-100 shadow">
                            <iframe class="w-full h-64"
                                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d106000.49300263195!2d151.1141413582031!3d-33.8763776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sau!4v1547515706012"
                                frameborder="0" style="border: 0" allowfullscreen></iframe>
                        </div>

                        <div class="bg-gray-100 rounded-xl shadow p-6">
                            <h3 class="text-lg font-bold text-indigo-800 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2h5" />
                                </svg>
                                Nos coordonnées
                            </h3>
                            <ul class="text-gray-700 text-sm space-y-2">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-indigo-600"></i> Antananarivo, Madagascar
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-phone-alt text-indigo-600"></i> +261 34 54 292 33
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-envelope text-indigo-600"></i> arsenefaly1@gmail.com
                                </li>
                            </ul>
                            <div class="mt-4 flex gap-4">
                                <a href="#" aria-label="Facebook"
                                    class="hover:text-indigo-600 text-gray-600 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" aria-label="Twitter" class="hover:text-indigo-600 text-gray-600 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" aria-label="Instagram"
                                    class="hover:text-indigo-600 text-gray-600 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </Section>
    </UserLayout>
</template>
