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
})

const form = useForm({
    name: '',
    nif: '',
    stat: '',
    adresse: '',
    contact: '',
    document: null,
})

watch(() => props.ecole, (newEcole) => {
    if (newEcole) {
        form.name = newEcole.name || ''
        form.nif = newEcole.nif || ''
        form.stat = newEcole.stat || ''
        form.adresse = newEcole.adresse || ''
        form.contact = newEcole.contact || ''
    }
}, { immediate: true })

function onFileChange(event) {
    const files = event.target.files
    if (files.length > 0) {
        form.document = files[0]
    } else {
        form.document = null
    }
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
    <div class="w-full mt-10 px-4 sm:px-6 lg:px-8 py-5 bg-gray-50 rounded-xl" ref="formRef">
        <TitleLayout title="Entrer votre Information" />

        <div v-if="hasEcoleData" class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200">
            Vos informations ont déjà été enregistrées. Vous pouvez les modifier si nécessaire.
        </div>

        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div class="space-y-2">
                    <Label for="name" class="text-md sm:text-lg label">Nom complet de l'école <span
                            class="text-red-500">*</span></Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name"
                        v-model="form.name" placeholder="ex: ESMIA"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.name" />

                    <Label for="nif" class="text-md sm:text-lg label">Nif<span
                            class="text-red-500">*</span></Label>
                    <Input id="nif" type="text" required autofocus :tabindex="1" autocomplete="nif" v-model="form.nif"
                        placeholder="ex: 123456789"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.nif" />

                    <Label for="stat" class="text-md sm:text-lg label">Stat <span
                            class="text-red-500">*</span></Label>
                    <Input id="stat" type="text" required autofocus :tabindex="1" autocomplete="stat"
                        v-model="form.stat" placeholder="ex: 987654321"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.stat" />

                    <Label for="adresse" class="text-md sm:text-lg label">Adresse exacte <span
                            class="text-red-500">*</span></Label>
                    <Input id="adresse" type="text" required autofocus :tabindex="1" autocomplete="adresse"
                        v-model="form.adresse" placeholder="ex: Mahamasina"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.adresse" />

                    <Label for="contact" class="text-md sm:text-lg label">Contact ou Email<span
                            class="text-red-500">*</span></Label>
                    <Input id="contact" type="text" required autofocus :tabindex="1" autocomplete="contact"
                        v-model="form.contact" placeholder="ex: 032 12 345 67 ou ex : exemple@gmail.com"
                        class="text-md sm:text-lg input" />
                    <InputError :message="form.errors.contact" />

                    <Label for="document" class="text-md sm:text-lg label">Document <span
                            class="text-red-500">*</span></Label>
                    <input id="document" type="file" @change="onFileChange"
                        class="block w-full border cursor-pointer border-gray-300 rounded-2xl py-3 px-4 text-base text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white" />
                    <InputError :message="form.errors.document" />
                </div>

                <Button type="submit" :tabindex="4" :disabled="form.processing"
                    class="cursor-pointer mt-6 w-[30%] flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-lg font-semibold text-white transition-all duration-200 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                    <span>{{ form.processing ? 'Veuillez patienter...' : 'Envoyer' }}</span>
                </Button>
            </div>
        </form>
    </div>


</template>
