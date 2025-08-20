<script setup>
import img from '/public/assets/image/fond/disposition-des-elements-de-travail-vue-de-dessus.jpg';

defineProps({
    name: String,
    description: String,
});
</script>

<template>
    <section class="relative overflow-hidden rounded-xl py-10 shadow-xl border border-gray-300 pt-[6rem] pb-[6rem]">
        <div class="absolute inset-0 z-0" id="profile">
            <!-- <img :src="img" alt="Image de fond" class="absolute inset-0 z-0 h-full w-full object-cover object-center" draggable="false" /> -->
            <!-- Overlay blanc semi-transparent avec flou -->
            <div class="absolute inset-0 bg-gray-200 w-full"></div>
        </div>

        <div class="relative z-10 mx-auto px-6">
            <div class="mb-20 text-center">
                <h2 id="title"
                    class="relative mb-3 inline-block bg-gradient-to-r from-indigo-700 via-purple-600 to-pink-500 bg-clip-text text-5xl font-extrabold tracking-tight text-transparent drop-shadow-lg">
                    {{ name }}
                </h2>

                <div class="flex justify-center">
                    <div class="h-1 w-28 rounded-full bg-indigo-500"></div>
                </div>

                <p id="desc" class="mx-auto mt-4 max-w-xl text-lg leading-relaxed text-indigo-700">
                    {{ description }}
                </p>
            </div>
        </div>

        <div class="relative z-10 mt-10 px-5 sm:px-10">
            <slot />
        </div>
    </section>
</template>
