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
import { ref, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';

// Props reçues depuis le contrôleur
const props = defineProps({
    schoolId: Number,
    categories: Array,
    niveaux: Array,
    modes: Array,
    langues: Array,
    statusOptions: Array,
    schoolCategory: String,
})

const form = useForm({
    school_id: props.schoolId,
    title: '',
    description: '',
    cover_photo: null,
    level: [], // ✅ doit correspondre à la validation Laravel
    duration: '',
    mode: '',
    language: 'fr',
    price: '',
    category: [],
    status: 'draft',
});

const previewCover = ref(null);

function onCoverPhotoChange(event) {
    const file = event.target.files?.[0] ?? null;
    form.cover_photo = file;

    if (previewCover.value && previewCover.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewCover.value);
    }
    previewCover.value = file ? URL.createObjectURL(file) : null;
}

const toggleAllLevels = () => {
  if (!props.niveaux) return;

  if (form.level.length === props.niveaux.length) {
    form.level = [];
  } else {
    form.level = [...props.niveaux];
  }
}



function goBack() {
    router.visit(route('owner.formation.status', { status: 'draft' }), { replace: true });
}

function submit() {
    form.post(route('owner.formation.store'), {
        onSuccess: () => {
            form.reset();
            previewCover.value = null;

            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'Formation enregistrée avec succès !',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        },
        onError: (errors) => {
            // Transforme les erreurs en texte lisible
            const errorMessages = Object.values(errors).flat().join('\n');

            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: errorMessages || 'Une erreur est survenue, veuillez réessayer.',
            });
        },
    });
}


onBeforeUnmount(() => {
    if (previewCover.value && previewCover.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewCover.value);
    }
});
</script>

<template>
    <UserLayout>
        <div class="pt-30 sm:pt-32 pb-12 px-0">
            <div class="bg-gray-50 shadow-lg w-full mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 sm:py-16">

                <!-- Bouton retour -->
                <div class="flex justify-end mb-6">
                    <button @click="goBack" type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-white border border-indigo-500 px-4 py-2 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 font-semibold shadow-sm transition duration-200">
                        <ChevronLeftIcon class="w-5 h-5" />
                        <span>Retour</span>
                    </button>
                </div>

                <!-- Titre -->
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-indigo-800">Ajouter une formation</h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">Remplissez le formulaire ci-dessous pour ajouter
                        une formation à votre école.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 sm:space-y-8">

                    <!-- Photo de couverture -->
                    <div>
                        <Label for="cover_photo" class="text-indigo-600 text-lg font-semibold">Photo de
                            couverture</Label>
                        <Input id="cover_photo" type="file" @change="onCoverPhotoChange" accept="image/*"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <div class="mt-3">
                            <img v-if="previewCover" :src="previewCover" alt="Aperçu cover"
                                class="w-full max-h-48 object-cover rounded-md border" />
                        </div>
                        <InputError :message="form.errors.cover_photo" />
                    </div>

                    <!-- Titre -->
                    <div>
                        <Label for="title" class="text-sm sm:text-base label">Nom de la formation <span
                                class="text-red-500">*</span></Label>
                        <Input id="title" v-model="form.title" type="text" required placeholder="ex: Développement Web"
                            class="input mt-2 text-sm sm:text-base" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div>
                        <Label for="description" class="text-indigo-600 text-lg font-semibold">Description</Label>
                        <textarea id="description" v-model="form.description" placeholder="Description" rows="4"
                            class="bg-white mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Niveaux -->
                    <div>
                        <Label class="text-indigo-600 text-lg font-semibold">Niveaux</Label>

                        <!-- Checkbox Sélectionner tous -->
                        <div class="mt-2 mb-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" :checked="form.level.length === props.niveaux.length"
                                    @change="toggleAllLevels" class="mr-2" />
                                Sélectionner tous
                            </label>
                        </div>

                        <!-- Liste des niveaux -->
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <label v-for="(niveau, idx) in props.niveaux" :key="idx" class="inline-flex items-center">
                                <input type="checkbox" :value="niveau" v-model="form.level" class="mr-2" />
                                {{ niveau }}
                            </label>
                        </div>

                        <InputError :message="form.errors.levels" />
                    </div>


                    <!-- Durée -->
                    <div>
                        <Label for="duration" class="text-indigo-600 text-lg font-semibold">Durée (mois)</Label>
                        <Input id="duration" v-model="form.duration" type="number" min="1" placeholder="ex: 12"
                            class="input mt-2" />
                        <InputError :message="form.errors.duration" />
                    </div>

                    <!-- Mode -->
                    <div>
                        <Label for="mode" class="text-indigo-600 text-lg font-semibold">Mode</Label>
                        <select id="mode" v-model="form.mode"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">Sélectionner le mode</option>
                            <option v-for="mode in modes" :key="mode" :value="mode">{{ mode }}</option>
                        </select>
                        <InputError :message="form.errors.mode" />
                    </div>

                    <!-- Langue -->
                    <div>
                        <Label for="language" class="text-indigo-600 text-lg font-semibold">Langue</Label>
                        <select id="language" v-model="form.language"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option v-for="lang in langues" :key="lang" :value="lang">{{ lang }}</option>
                        </select>
                        <InputError :message="form.errors.language" />
                    </div>

                    <!-- Prix -->
                    <div>
                        <Label for="price" class="text-indigo-600 text-lg font-semibold">Prix (€)</Label>
                        <Input id="price" v-model="form.price" type="number" step="0.01" placeholder="ex: 1200.00"
                            class="input mt-2" />
                        <InputError :message="form.errors.price" />
                    </div>

                    <!-- Catégories -->
                    <div>
                        <Label class="text-indigo-600 text-lg font-semibold">Catégories</Label>
                        <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <label v-for="cat in categories" :key="cat" class="inline-flex items-center gap-2">
                                <input type="checkbox" :value="cat" v-model="form.category"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-gray-800">{{ cat }}</span>
                            </label>
                        </div>
                        <InputError :message="form.errors.category" />
                    </div>

                    <!-- Statut -->
                    <div>
                        <Label for="status" class="text-indigo-600 text-lg font-semibold">Statut</Label>
                        <select id="status" v-model="form.status"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option v-for="status in statusOptions" :key="status" :value="status">{{ status }}</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <!-- Bouton Ajouter -->
                    <div class="pt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="w-full md:w-1/3 flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2 text-white font-semibold text-base sm:text-lg transition duration-200 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <LoaderCircle v-if="form.processing" class="h-5 w-5 animate-spin" />
                            <span>{{ form.processing ? 'Veuillez patienter...' : 'Ajouter' }}</span>
                        </Button>
                    </div>

                </form>
            </div>
        </div>
    </UserLayout>
</template>
