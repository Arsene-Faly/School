<script setup>
import { ref, watch } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import Label from '@/components/ui/label/Label.vue'
import Input from '@/components/ui/input/Input.vue'
import InputError from '@/components/InputError.vue'
import Button from '@/components/ui/button/Button.vue'
import { LoaderCircle } from 'lucide-vue-next'
import Swal from 'sweetalert2'
import SectionDefault from '@/components/app/user/section/SectionDefault.vue'
import AuthProfile from './page/AuthProfile.vue'
import TitleLayout from '@/components/app/TitleLayout.vue'
import Section from '@/components/app/user/section/Section.vue'
import UserLayout from '@/layouts/User/UserLayout.vue'

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
    form.post(route('profile.detail.update'), {
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
    <UserLayout>
        <SectionDefault title="Page De Profile">
        </SectionDefault>

        <Section name="Profiles Informations">
            <AuthProfile>
                <div class="mx-auto mb-20 rounded-3xl p-0 sm:px-10 py-14 md:px-16 flex flex-col gap-12">

                    <div>
                        <TitleLayout title="Plus d'informations sur votre profil" />
                        <p class="text-gray-500 text-md mt-2 mb-6">Complétez vos informations personnelles pour un
                            profil
                            complet.</p>

                        <form @submit.prevent="submit" class="space-y-8">

                            <!-- Avatar -->
                            <div>
                                <Label for="avatar" class="block text-sm font-semibold text-indigo-600 mb-2">Photo de
                                    profil</Label>
                                <div class="flex items-center gap-5">
                                    <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar Preview"
                                        class="w-20 h-20 rounded-full object-cover border" />
                                    <img v-else :src="`/${userInfo.avatar}`" alt="Avatar"
                                        class="w-20 h-20 rounded-full object-cover border" />
                                    <input id="avatar" type="file" accept="image/*" @change="onFileChange"
                                        class="text-sm text-gray-600" />
                                </div>
                                <InputError :message="form.errors.avatar" class="mt-1" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <Label for="phone"
                                    class="block text-sm font-semibold text-indigo-600 mb-2">Téléphone</Label>
                                <Input id="phone" type="text" v-model="form.phone" placeholder="+261 34 00 000 00"
                                    class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                                <InputError :message="form.errors.phone" class="mt-1" />
                            </div>

                            <!-- Adresse -->
                            <div>
                                <Label for="address"
                                    class="block text-sm font-semibold text-indigo-600 mb-2">Adresse</Label>
                                <Input id="address" type="text" v-model="form.address" placeholder="Antananarivo"
                                    class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                                <InputError :message="form.errors.address" class="mt-1" />
                            </div>

                            <!-- Date de naissance -->
                            <div>
                                <Label for="birthdate" class="block text-sm font-semibold text-indigo-600 mb-2">Date de
                                    naissance</Label>
                                <Input id="birthdate" type="date" v-model="form.birthdate"
                                    class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                                <InputError :message="form.errors.birthdate" class="mt-1" />
                            </div>

                            <!-- Genre -->
                            <div>
                                <Label for="gender"
                                    class="block text-sm font-semibold text-indigo-600 mb-2">Genre</Label>
                                <select id="gender" v-model="form.gender"
                                    class="w-full rounded-xl border border-gray-300 px-5 py-2 text-gray-700 text-base shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">Sélectionner</option>
                                    <option value="male">Homme</option>
                                    <option value="female">Femme</option>
                                    <option value="other">Autre</option>
                                </select>
                                <InputError :message="form.errors.gender" class="mt-1" />
                            </div>

                            <!-- Bio -->
                            <div>
                                <Label for="bio"
                                    class="block text-sm font-semibold text-indigo-600 mb-2">Biographie</Label>
                                <textarea id="bio" v-model="form.bio" rows="4" placeholder="Parlez un peu de vous..."
                                    class="w-full rounded-xl border border-gray-300 px-5 py-4 text-gray-700 text-base shadow-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                                <InputError :message="form.errors.bio" class="mt-1" />
                            </div>

                            <!-- Bouton -->
                            <div class="flex justify-end">
                                <Button type="submit" :disabled="form.processing"
                                    class="flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-white font-semibold text-base hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                                    <span>{{ form.processing ? 'Veuillez patienter...' : 'Modifier' }}</span>
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </AuthProfile>
        </Section>
    </UserLayout>
</template>
