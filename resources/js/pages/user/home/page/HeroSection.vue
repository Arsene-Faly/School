<script setup>
import TextLink from '@/components/TextLink.vue';
import img1 from '/public/assets/image/IMG/2.jfif'; // cercle
import img2 from '/public/assets/image/IMG/3.jfif';
import img3 from '/public/assets/image/IMG/7.jfif';

</script>

<template>
  <section
    id="section"
    class="relative flex min-h-[85vh] items-center overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-900 to-purple-900 px-6 pt-32 pb-32 text-white shadow-lg"
  >
    <video autoplay muted loop playsinline class="absolute inset-0 z-0 h-full w-full object-cover">
      Votre navigateur ne supporte pas la vidéo.
    </video>

    <div class="absolute inset-0 z-10 bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-900 opacity-70"></div>

    <div class="relative z-20 mx-auto flex w-full flex-col-reverse items-center gap-16 px-4 lg:flex-row">
      <div class="flex-1 text-center lg:text-left">
        <h1 class="mb-8 text-4xl sm:text-5xl lg:text-6xl leading-tight font-extrabold font" id="title">
          Trouvez l’ecole qui vous inspire
        </h1>
        <p class="mx-auto mb-10 max-w-xl text-lg sm:text-xl lg:text-2xl text-slate-200 lg:mx-0" id="desc">
          Explorez des établissements de qualité, découvrez les programmes adaptés à votre avenir, et faites le bon choix.
        </p>
        <div class="flex justify-center gap-6 lg:justify-start">
          <TextLink :href="route('etablissement.categorie', { categorie: 'scolaire' })"
            id="btn"
            class="cursor-pointer rounded-2xl bg-white px-6 sm:px-9 py-3 sm:py-4 font-semibold text-indigo-800 shadow-md transition hover:bg-slate-100"
          >
            Explorer les écoles
          </TextLink>
          <TextLink :href="route('etablissement.categorie', { categorie: 'scolaire' })"
            id="btn"
            class="cursor-pointer rounded-2xl border border-white px-6 sm:px-9 py-3 sm:py-4 text-white transition hover:bg-white hover:text-indigo-800"
          >
            En savoir plus
          </TextLink>
        </div>
      </div>

      <!-- Images (cachées sur petits écrans) -->
      <div class="relative h-[600px] w-full flex-1 hidden 2xl:block">
        <img
          :src="img1"
          alt="Étudiant 1"
          class="absolute top-0 left-0 h-72 w-72 rounded-full border-4 border-white object-cover shadow-2xl"
          loading="lazy"
        />
        <img
          :src="img2"
          alt="Étudiant 2"
          class="absolute top-32 left-52 h-80 w-72 rounded-3xl border-4 border-white object-cover shadow-xl"
          loading="lazy"
        />
        <img
          :src="img3"
          alt="Étudiant 3"
          class="absolute top-56 left-[26rem] h-72 w-72 rounded-lg border-4 border-white object-cover shadow-lg"
          loading="lazy"
        />
      </div>
    </div>
  </section>
</template>

<style scoped>

</style>
