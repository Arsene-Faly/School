<script setup>
import TextLink from '@/components/TextLink.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    }
});

let activites = [];

if (props.data?.schools) {
    activites = props.data.schools.flatMap(school => {
        const formations = school.formations?.map(f => ({
            titre: f.title,
            slug: f.slug, // ✅ on ajoute le slug
            type: 'formation',
            image: f.cover_photo || '/default.jpg',
            description: f.description || '',
            colSpan: '',
        })) || [];

        const activitesList = school.activity?.map(a => ({
            titre: a.title,
            slug: a.slug, // ✅ on ajoute le slug
            type: 'activite',
            image: a.cover_photo || '/default.jpg',
            description: a.description || '',
            colSpan: '',
        })) || [];

        return [...formations, ...activitesList];
    });

    if (activites.length > 0) {
        activites[0].colSpan = 'md:col-span-2 md:row-span-2';
    }
}
</script>

<template>
    <div>
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 auto-rows-[200px] md:auto-rows-[250px]">
                <div v-for="(item, index) in activites.slice(0, 5)" :key="index"
                    class="relative overflow-hidden rounded-2xl shadow-xl group cursor-pointer transform transition-all"
                    :class="item.colSpan">
                    <TextLink v-if="item.slug" :href="route('programme.show', {
                        categorie: item.type === 'formation' ? 'formation' : 'activite',
                        slug: item.slug
                    })">
                        <!-- Image -->
                        <img :src="item.image" :alt="item.titre"
                            class="w-full h-full object-cover duration-500 group-hover:scale-110" loading="lazy" />

                        <!-- Badge type -->
                        <span
                            class="absolute top-3 left-3 text-xs uppercase font-bold px-3 py-1 rounded-full text-white shadow-md"
                            :class="item.type === 'formation' ? 'bg-yellow-400' : 'bg-indigo-600'">
                            {{ item.type }}
                        </span>

                        <!-- Overlay dégradé -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>

                        <!-- Détails au survol -->
                        <div
                            class="absolute bottom-0 left-0 right-0 p-5 text-white transition-all duration-500 translate-y-full group-hover:translate-y-0">
                            <h3 class="text-lg font-bold md:text-xl">{{ item.titre }}</h3>
                            <p v-if="item.description" class="text-sm mt-1 line-clamp-3">
                                {{ item.description }}
                            </p>
                        </div>
                    </TextLink>
                </div>
            </div>
        </div>

        <!-- Bouton Voir Tous -->
        <div class="mx-auto w-full flex justify-center mt-10">
            <TextLink :href="route('programme.categorie', { categorie: 'formation' })"
                class="inline-block rounded-xl bg-indigo-600 px-8 py-3 text-lg font-semibold text-white transition-colors duration-300 hover:bg-indigo-700">
                Voir Tous
            </TextLink>
        </div>
    </div>
</template>
