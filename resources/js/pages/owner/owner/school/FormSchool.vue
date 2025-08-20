<script setup>
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Swal from 'sweetalert2';

const props = defineProps({
    data: Object
})


const form = useForm({
    name: props.data.school.name,
    nif: props.data.school.nif,
    stat: props.data.school.stat,
    adresse: props.data.school.adresse,
    contact: props.data.school.contact,
    category_id: props.data.school.category_id || '',
    type: props.data.school.type ?? 'public',
})

function goBack() {
    window.history.back();
}

const submit = () => {
    form.post(route('owner.update.school'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Ecole mis à jour !',
                text: 'Vos informations ont été enregistrées avec succès.',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            })
        },
    })
}
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div
                class="bg-gray-50 shadow-lg rounded-2xl w-full max-w-7xl mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">
                        Modifier les informations de l’établissement
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Veuillez remplir les champs ci-dessous pour mettre à jour les informations de votre école.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">
                    <div>
                        <Label for="name" class="text-sm sm:text-base label">Nom complet de l'école <span
                                class="text-red-500">*</span></Label>
                        <Input id="name" v-model="form.name" type="text" required autocomplete="name"
                            placeholder="ex: ESMIA" class="input mt-2 text-sm sm:text-base" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="type" class="text-sm sm:text-base label">Type d'établissement <span
                                class="text-red-500">*</span></Label>
                        <select id="type" v-model="form.type" required
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-xl py-2 px-3 text-sm sm:text-base focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="prive">Privé</option>
                            <option value="public">Public</option>
                        </select>
                        <InputError :message="form.errors.type" />
                    </div>

                    <div>
                        <Label for="category" class="text-sm sm:text-base label">Catégorie de l'école <span
                                class="text-red-500">*</span></Label>
                        <select id="category" v-model="form.category_id" required
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-xl py-2 px-3 text-sm sm:text-base focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">-- Sélectionnez une catégorie --</option>
                            <option v-for="category in props.data.category" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.category_id" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <Label for="nif" class="text-sm sm:text-base label">NIF <span
                                    class="text-red-500">*</span></Label>
                            <Input id="nif" v-model="form.nif" type="text" placeholder="ex: 123456789" required
                                class="input mt-2 text-sm sm:text-base" />
                            <InputError :message="form.errors.nif" />
                        </div>

                        <div>
                            <Label for="stat" class="text-sm sm:text-base label">Stat <span
                                    class="text-red-500">*</span></Label>
                            <Input id="stat" v-model="form.stat" type="text" placeholder="ex: 987654321" required
                                class="input mt-2 text-sm sm:text-base" />
                            <InputError :message="form.errors.stat" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <Label for="adresse" class="text-sm sm:text-base label">Adresse exacte <span
                                    class="text-red-500">*</span></Label>
                            <Input id="adresse" v-model="form.adresse" type="text" placeholder="ex: Mahamasina" required
                                class="input mt-2 text-sm sm:text-base" />
                            <InputError :message="form.errors.adresse" />
                        </div>

                        <div>
                            <Label for="contact" class="text-sm sm:text-base label">Contact ou Email <span
                                    class="text-red-500">*</span></Label>
                            <Input id="contact" v-model="form.contact" type="text"
                                placeholder="ex: 032 12 345 67 ou exemple@gmail.com" required
                                class="input mt-2 text-sm sm:text-base" />
                            <InputError :message="form.errors.contact" />
                        </div>
                    </div>

                    <div class="pt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="cursor-pointer w-full md:w-1/3 flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold text-base sm:text-lg transition duration-200 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                            <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>
