<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import L from 'leaflet';
import Label from '@/components/ui/label/Label.vue';
import { ChevronLeftIcon } from '@heroicons/vue/24/outline';
import UserLayout from '@/layouts/User/UserLayout.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps({
    schoolInfo: {
        type: Object,
        default: null,
    },
});

const previewLogo = ref(null);
const previewCover = ref(null);
const existingLogoUrl = ref(props.schoolInfo?.logo ?? null);
const existingCoverUrl = ref(props.schoolInfo?.cover_photo ?? null);

const form = useForm({
    latitude: props.schoolInfo?.latitude ?? null,
    longitude: props.schoolInfo?.longitude ?? null,
    logo: null,
    cover_photo: null,
    description: props.schoolInfo?.description ?? '',
    date_creation: props.schoolInfo?.date_creation ?? '',          // Ajout date_creation
    nombre_etudiants: props.schoolInfo?.nombre_etudiants ?? 0,    // Ajout nombre_etudiants
});
let map;
let marker;

function initMap() {
    const lat = form.latitude ? parseFloat(form.latitude) : -18.8792;
    const lng = form.longitude ? parseFloat(form.longitude) : 47.5079;

    map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map);

    if (form.latitude && form.longitude) {
        marker = L.marker([parseFloat(form.latitude), parseFloat(form.longitude)]).addTo(map);
    }

    map.on('click', (e) => {
        form.latitude = e.latlng.lat.toFixed(6);
        form.longitude = e.latlng.lng.toFixed(6);

        if (marker) marker.remove();
        marker = L.marker([parseFloat(form.latitude), parseFloat(form.longitude)]).addTo(map);
    });
}

onMounted(() => initMap());

onBeforeUnmount(() => {
    if (map) {
        map.off();
        map.remove();
    }
    if (previewLogo.value && previewLogo.value.startsWith('blob:')) URL.revokeObjectURL(previewLogo.value);
    if (previewCover.value && previewCover.value.startsWith('blob:')) URL.revokeObjectURL(previewCover.value);
});

watch(() => props.schoolInfo, (newInfo) => {
    existingLogoUrl.value = newInfo?.logo ?? null;
    existingCoverUrl.value = newInfo?.cover_photo ?? null;
    form.latitude = newInfo?.latitude ?? form.latitude;
    form.longitude = newInfo?.longitude ?? form.longitude;
    form.description = newInfo?.description ?? form.description;
    form.date_creation = newInfo?.date_creation ?? form.date_creation;
    form.nombre_etudiants = newInfo?.nombre_etudiants ?? form.nombre_etudiants;
}, { immediate: false });

function onLogoChange(event) {
    const file = event.target.files?.[0] ?? null;
    form.logo = file;

    if (previewLogo.value && previewLogo.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewLogo.value);
    }

    previewLogo.value = file ? URL.createObjectURL(file) : null;
}

function onCoverPhotoChange(event) {
    const file = event.target.files?.[0] ?? null;
    form.cover_photo = file;

    if (previewCover.value && previewCover.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewCover.value);
    }

    previewCover.value = file ? URL.createObjectURL(file) : null;
}

function submit() {
    form.post(route('owner.updateInfo.school'), {
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: 'École enregistrée avec succès !',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue, veuillez réessayer.',
            });
        },
    });
}
function goBack() {
    window.history.back()
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
                        Informations détaillées
                    </h2>
                    <p class="mt-2 text-base sm:text-lg text-gray-600">
                        Veuillez remplir les champs ci-dessous pour mettre à jour les informations de votre école.
                    </p>
                </div>

                <Label for="latitude" class="text-indigo-600 text-lg font-semibold">Sélectionner votre lieux</Label>
                <div id="map" class="mb-6"></div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Latitude -->
                    <div>
                        <Label for="latitude" class="text-indigo-600 text-lg font-semibold">Latitude</Label>
                        <Input id="latitude" type="text" v-model="form.latitude" readonly placeholder="ex: -18.879200"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Longitude -->
                    <div>
                        <Label for="longitude" class="text-indigo-600 text-lg font-semibold">Longitude</Label>
                        <Input id="longitude" type="text" v-model="form.longitude" readonly placeholder="ex: 47.507900"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Logo -->
                    <div>
                        <Label for="logo" class="text-indigo-600 text-lg font-semibold">Logo</Label>
                        <Input id="logo" type="file" @change="onLogoChange" accept="image/*"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <div class="mt-3">
                            <img v-if="previewLogo" :src="previewLogo" alt="Aperçu logo"
                                class="w-32 h-32 object-cover rounded-md border" />
                            <img v-else-if="existingLogoUrl" :src="existingLogoUrl" alt="Logo actuel"
                                class="w-32 h-32 object-cover rounded-md border" />
                        </div>
                    </div>

                    <!-- Cover Photo -->
                    <div>
                        <Label for="cover_photo" class="text-indigo-600 text-lg font-semibold">Photo de
                            couverture</Label>
                        <Input id="cover_photo" type="file" @change="onCoverPhotoChange" accept="image/*"
                            class="mt-2 block w-full border border-gray-300 bg-white rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <div class="mt-3">
                            <img v-if="previewCover" :src="previewCover" alt="Aperçu cover"
                                class="w-full max-h-48 object-cover rounded-md border" />
                            <img v-else-if="existingCoverUrl" :src="existingCoverUrl" alt="Cover actuelle"
                                class="w-full max-h-48 object-cover rounded-md border" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <Label for="description" class="text-indigo-600 text-lg font-semibold">Description</Label>
                        <textarea id="description" v-model="form.description" placeholder="Description" rows="4"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-2 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                    </div>

                    <!-- Date création -->
                    <div>
                        <Label for="date_creation" class="text-indigo-600 text-lg font-semibold">Date de
                            création</Label>
                        <Input id="date_creation" type="date" v-model="form.date_creation"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <!-- Nombre d'étudiants -->
                    <div>
                        <Label for="nombre_etudiants" class="text-indigo-600 text-lg font-semibold">Nombre
                            d'étudiants</Label>
                        <Input id="nombre_etudiants" type="number" v-model.number="form.nombre_etudiants" min="0"
                            class="mt-2 block w-full border border-gray-300 rounded-2xl py-1 px-4 text-xl text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                        :disabled="form.processing">
                        Enregistrer
                    </button>
                </form>

            </div>
        </div>
    </UserLayout>
</template>

<style>
@import "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css";

#map {
  width: 100%;
  height: 400px;
  z-index: 0;
}
</style>
