<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import img from '/public/assets/image/livre-ouvert.png'
import img1 from '/public/assets/image/ajouter-un-bouton.png'
import img2 from '/public/assets/image/vue (1).png'
import img3 from '/public/assets/image/image.png'
import img4 from '/public/assets/image/retour-dinformation.png'
import img5 from '/public/assets/image/lobtention-du-diplome.png'
import img6 from '/public/assets/image/couverture-de-livre.png'
import img7 from '/public/assets/image/information.png'

const cards = ref([
    {
        route: { name: 'owner.edit.school' },
        title: 'Informations générales',
        description:
            'Modifier le nom, l’adresse, le logo ou les contacts de votre établissement pour une meilleure visibilité.',
        icon: img,
    },
    {
        route: { name: 'owner.editInfo.school' },
        title: 'Informations détaillées',
        description:
            'Gérer les conditions d’admission, règlements, descriptions et toutes les informations avancées de votre établissement.',
        icon: img1,
    },
    {
        route: { name: 'owner.about.school' },
        title: 'A propos de votre écoles',
        description:
            'Gérer les conditions d’admission, règlements, descriptions et toutes les informations avancées de votre établissement.',
        icon: img7,
    },
    {
        route: { name: 'owner.gallery.school' },
        title: 'Gérer des photos',
        description:
            'Consultez un aperçu complet sur les gallery.',
        icon: img3,
    },
    {
        route: { name: 'owner.testimonials.school' },
        title: 'Gérer les avis des utilisateurs',
        description:
            'Consultez un aperçu complet sur témoinage',
        icon: img4,
    },
    {
        route: { name: 'owner.formation.status', params: { status: 'published' } },
        title: 'Gérer les programmes',
        description:
            'Consultez et administrez vos programmes publiés.',
        icon: img5,
    },
    {
        route: { name: 'owner.activity.status', params: { status: 'published' } },
        title: 'Gérer les activitées',
        description:
            'Consultez et administrez vos programmes activitées.',
        icon: img6,
    },
    {
        route: { name: 'owner.show.school' },
        title: 'Views écoles',
        description:
            'Consultez un aperçu complet et structuré de votre établissement tel qu’il sera présenté aux visiteurs.',
        icon: img2,
    },
])

function navigateTo(routeObj) {
    const url = route(routeObj.name, routeObj.params || {})
    router.get(url)
}
</script>

<template>
    <section class="px-4 py-14 sm:px-10 lg:px-24 bg-gray-100 rounded-3xl" aria-label="Espace de gestion établissement">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-12">
            <div v-for="card in cards" :key="card.title" tabindex="0" role="button"
                :aria-label="`Naviguer vers ${card.title}`" class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100 cursor-pointer transition-transform duration-300 ease-in-out
               hover:shadow-2xl hover:border-indigo-500 hover:scale-[1.03]
               focus:outline-none focus:ring-4 focus:ring-indigo-300" @click="navigateTo(card.route)"
                @keydown.enter="navigateTo(card.route)">
                <img :src="card.icon" :alt="card.title" class="w-24 h-24 mx-auto mb-6" loading="lazy"
                    decoding="async" />
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ card.title }}</h3>
                    <p class="text-gray-600 text-base leading-relaxed">{{ card.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
