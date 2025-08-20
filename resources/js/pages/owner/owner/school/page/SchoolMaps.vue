<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { MapIcon } from '@heroicons/vue/24/outline';

// Fix icône par défaut (problème fréquent avec Vite/Webpack)
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
    iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
    shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
});

const props = defineProps({
    school: {
        type: Object,
        default: () => ({}),
    },
});

const mapRef = ref(null);
let map = null;
let marker = null;

const DEFAULT_COORDS = { lat: -18.8792, lng: 47.5079 }; // Antananarivo par défaut
const DEFAULT_ZOOM = 13;

function isValidCoord(v) {
    return typeof v !== 'undefined' && v !== null && !Number.isNaN(Number(v));
}

function initMap(lat = DEFAULT_COORDS.lat, lng = DEFAULT_COORDS.lng) {
    if (!mapRef.value || map) return;
    map = L.map(mapRef.value, { attributionControl: false }).setView([lat, lng], DEFAULT_ZOOM);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    marker = L.marker([lat, lng]).addTo(map);
}

function setMarkerAndView(lat, lng, label = '') {
    if (!map) return;
    if (marker) {
        marker.setLatLng([lat, lng]);
        if (label) marker.bindPopup(label);
    } else {
        marker = L.marker([lat, lng]).addTo(map).bindPopup(label);
    }
    map.setView([lat, lng], DEFAULT_ZOOM);
}

async function setupFromProps() {
    if (!props.school || !props.school.info) return; // ne rien faire si pas de données
    await nextTick();

    const lat = parseFloat(props.school.info.latitude);
    const lng = parseFloat(props.school.info.longitude);

    if (!isValidCoord(lat) || !isValidCoord(lng)) return; // ne pas afficher la carte si coordonnées invalides

    initMap(lat, lng);
    setMarkerAndView(lat, lng, props.school.name ?? '');
}

onMounted(() => {
    setupFromProps();
});

watch(() => props.school, async () => {
    await setupFromProps();
}, { deep: true });

onBeforeUnmount(() => {
    if (map) {
        map.off();
        map.remove();
        map = null;
    }
    marker = null;
});
</script>

<template>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8 p-4">
        <div class="relative">
            <h2 class="text-2xl font-bold text-indigo-900 mb-6 flex items-center">
                <MapIcon class="w-6 h-6 text-indigo-600 mr-2" />
                Maps
            </h2>

            <div v-if="props.school?.info && props.school.info.latitude && props.school.info.longitude"
                 ref="mapRef"
                 id="map"
                 class="w-full h-96 rounded-lg shadow-md"
                 role="region"
                 aria-label="Carte de localisation de l'école">
            </div>

            <div v-else class="p-6 bg-gray-50 text-gray-600 rounded-lg border border-gray-200 text-center">
                Coordonnées non renseignées — la carte ne peut pas être affichée
            </div>
        </div>
    </div>
</template>

<style scoped>
#map {
    min-height: 240px;
    height: 24rem;
}
</style>
