<script setup>
import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
import TitleLayout from '@/components/app/TitleLayout.vue'
import Label from '@/components/ui/label/Label.vue'
import Input from '@/components/ui/input/Input.vue'
import InputError from '@/components/InputError.vue'
import { LoaderCircle } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps({
    ecole: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    }
})

const form = useForm({
    name: '',
    nif: '',
    stat: '',
    adresse: '',
    contact: '',
    document: null,
    category_id: '',
    type: 'public',
})

watch(() => props.ecole, (newEcole) => {
    if (newEcole) {
        form.name = newEcole.name || ''
        form.nif = newEcole.nif || ''
        form.stat = newEcole.stat || ''
        form.adresse = newEcole.adresse || ''
        form.contact = newEcole.contact || ''
        form.category_id = newEcole.category_id || ''
        form.type = newEcole.type || 'public'
    }
}, { immediate: true })

function onFileChange(event) {
    const files = event.target.files
    form.document = files.length > 0 ? files[0] : null
}

const submit = () => {
    form.post(route('owner.pending.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Profil mis à jour !',
                text: 'Vos informations ont été enregistrées avec succès.',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            })
        },
    })
}

const hasEcoleData = computed(() => !!props.ecole)
</script>

<template>
    <div class="w-full mt-10 px-4 sm:px-6 lg:px-8 py-5 bg-gray-50 rounded-xl">
        <TitleLayout title="Entrer votre Information" />

        <div v-if="hasEcoleData" class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            Vos informations ont déjà été enregistrées. Vous pouvez les modifier si nécessaire.
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2">
                <Label for="name" class="text-sm sm:text-base label">Nom complet de l'école <span class="text-red-500">*</span></Label>
                <Input id="name" class="input mt-2 text-sm sm:text-base" type="text" v-model="form.name" required placeholder="ex: ESMIA" />
                <InputError :message="form.errors.name" />

                <!-- Type -->
                <Label for="type" class="text-sm sm:text-base label">Type d'établissement <span class="text-red-500">*</span></Label>
                <select id="type"  v-model="form.type" required
                    class="block w-full border cursor-pointer border-gray-300 rounded-2xl py-3 px-4 text-base text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                    <option value="public">Public</option>
                    <option value="prive">Privé</option>
                </select>
                <InputError :message="form.errors.type" />

                <!-- Category -->
                <Label for="category" class="text-sm sm:text-base label">Catégorie de l'école <span class="text-red-500">*</span></Label>
                <select id="category" v-model="form.category_id" required
                    class="block w-full border cursor-pointer border-gray-300 rounded-2xl py-3 px-4 text-base text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <InputError :message="form.errors.category_id" />

                <!-- NIF -->
                <Label for="nif" class="text-sm sm:text-base label">NIF <span class="text-red-500">*</span></Label>
                <Input id="nif" class="input mt-2 text-sm sm:text-base" type="text" v-model="form.nif" required placeholder="ex: 123456789" />
                <InputError :message="form.errors.nif" />

                <!-- Stat -->
                <Label for="stat" class="text-sm sm:text-base label">Stat <span class="text-red-500">*</span></Label>
                <Input id="stat" class="input mt-2 text-sm sm:text-base" type="text" v-model="form.stat" required placeholder="ex: 987654321" />
                <InputError :message="form.errors.stat" />

                <!-- Adresse -->
                <Label for="adresse" class="text-sm sm:text-base label">Adresse exacte <span class="text-red-500">*</span></Label>
                <Input id="adresse" class="input mt-2 text-sm sm:text-base" type="text" v-model="form.adresse" required placeholder="ex: Mahamasina" />
                <InputError :message="form.errors.adresse" />

                <!-- Contact -->
                <Label for="contact" class="text-sm sm:text-base label">Contact ou Email <span class="text-red-500">*</span></Label>
                <Input id="contact" class="input mt-2 text-sm sm:text-base" type="text" v-model="form.contact" required placeholder="ex: 032 12 345 67 ou exemple@gmail.com" />
                <InputError :message="form.errors.contact" />

                <!-- Document -->
                <Label for="document" class="text-sm sm:text-base label">Document <span class="text-red-500">*</span></Label>
                <input id="document" type="file" @change="onFileChange"
                    class="block w-full border cursor-pointer border-gray-300 rounded-2xl py-3 px-4 text-base text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white" />
                <InputError :message="form.errors.document" />
            </div>

            <Button type="submit" :disabled="form.processing" class="mt-6 w-[30%] flex items-center justify-center gap-2 bg-indigo-600 text-white rounded-2xl px-6 py-3 hover:bg-indigo-700 disabled:opacity-50">
                <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                <span>{{ form.processing ? 'Veuillez patienter...' : 'Envoyer' }}</span>
            </Button>
        </form>
    </div>
</template>
