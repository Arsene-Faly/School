<script setup>
import { ref, watch } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import Label from '@/components/ui/label/Label.vue'
import Input from '@/components/ui/input/Input.vue'
import InputError from '@/components/InputError.vue'
import Button from '@/components/ui/button/Button.vue'
import { LoaderCircle } from 'lucide-vue-next'
import Swal from 'sweetalert2'
import TitleLayout from './Layout/TitleLayout.vue'
import AuthProfile from './AuthProfile.vue'

const { props } = usePage()
const userInfo = props.auth.user.info ?? {}

const avatarPreview = ref(userInfo.avatar_url ?? null)

const form = useForm({
    avatar: userInfo.avatar_url ?? null,
    phone: userInfo.phone,
    address: userInfo.address,
    birthdate: userInfo.birthdate,
    gender: userInfo.gender,
    bio: userInfo.bio,
})

watch(() => form.avatar, (avatar) => {
    if (avatar && typeof avatar !== 'string') {
        avatarPreview.value = URL.createObjectURL(avatar)
    }
})

const onFileChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.avatar = file
    }
}

const submit = () => {
    form.post(route('profileNewDetail.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (page) => {
            if (page.props.auth.user.info.avatar) {
                userInfo.avatar = page.props.auth.user.info.avatar
                avatarPreview.value = `/${userInfo.avatar}`
            }

            Swal.fire({
                icon: 'success',
                title: 'Informations mises à jour !',
                text: 'Vos informations personnelles ont été enregistrées avec succès.',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK',
            })
        },
    })
}

</script>

<template>
    <AuthProfile>
        <div class="w-full mb-[5rem]">
            <TitleLayout title="Plus d'informations sur votre profil" />

            <form @submit.prevent="submit">
                <div class="space-y-4 max-w-xl">
                    <div>
                        <Label for="avatar" class="text-indigo-600 text-lg font-semibold">Photo de profil</Label>
                        <div class="flex items-center gap-4 mt-2">
                            <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar Preview"
                                class="w-20 h-20 rounded-full object-cover border" />
                            <img v-else :src="`/${userInfo.avatar}`" alt="Avatar"
                                class="w-20 h-20 rounded-full object-cover border" />
                            <input id="avatar" type="file" accept="image/*" @change="onFileChange" class="text-sm" />
                        </div>
                        <InputError :message="form.errors.avatar" />
                    </div>


                    <div>
                        <Label for="phone" class="text-indigo-600 text-lg font-semibold">Téléphone</Label>
                        <Input id="phone" type="text" v-model="form.phone" placeholder="+261 34 00 000 00"
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div>
                        <Label for="address" class="text-indigo-600 text-lg font-semibold">Adresse</Label>
                        <Input id="address" type="text" v-model="form.address" placeholder="Antananarivo"
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <InputError :message="form.errors.address" />
                    </div>

                    <div>
                        <Label for="birthdate" class="text-indigo-600 text-lg font-semibold">Date de naissance</Label>
                        <Input id="birthdate" type="date" v-model="form.birthdate"
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <InputError :message="form.errors.birthdate" />
                    </div>

                    <div>
                        <Label for="gender" class="text-indigo-600 text-lg font-semibold">Genre</Label>
                        <select id="gender" v-model="form.gender"
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Sélectionner</option>
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                            <option value="other">Autre</option>
                        </select>
                        <InputError :message="form.errors.gender" />
                    </div>

                    <div>
                        <Label for="bio" class="text-indigo-600 text-lg font-semibold">Biographie</Label>
                        <textarea id="bio" v-model="form.bio" rows="4" placeholder="Parlez un peu de vous..."
                            class="block w-full border border-gray-300 bg-white rounded-2xl py-5 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                        <InputError :message="form.errors.bio" />
                    </div>

                    <Button type="submit" :disabled="form.processing"
                        class="cursor-pointer mt-6 w-[40%] flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-lg font-semibold text-white transition-all duration-200 hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                        <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                    </Button>

                </div>
            </form>
        </div>
    </AuthProfile>
</template>
