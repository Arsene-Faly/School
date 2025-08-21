<script setup>
import { ref } from 'vue';
import FormValidate from './FormValidate.vue';
import { ChevronDownIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    ecole: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    }
})

const formRef = ref(null)

function scrollToForm() {
    formRef.value?.$el?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
    <div class="w-full mx-auto mt-6 px-4 sm:px-6 lg:px-8 py-10 rounded-3xl bg-gradient-to-r from-yellow-50 via-yellow-100 to-yellow-50 shadow-2xl border-l-8 border-yellow-400 animate-fade-slide"
        role="alert">
        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
            <div
                class="flex items-center justify-center rounded-full bg-yellow-200 text-yellow-600 p-5 shadow-inner shadow-yellow-300">
                <ExclamationTriangleIcon class="w-14 h-14" />
            </div>

            <div class="flex-1">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-yellow-900 mb-2 drop-shadow-sm">
                    Compte en attente de validation
                </h2>

                <p class="text-yellow-700 italic mb-4 text-sm sm:text-base">
                    La validation peut prendre jusqu’à <span class="font-semibold">48 heures</span>. Merci pour votre
                    patience.
                </p>

                <p class="text-yellow-800 text-sm sm:text-base leading-relaxed mb-4">
                    Votre compte propriétaire est en cours de vérification. Merci de soumettre les informations de votre
                    établissement pour finaliser votre inscription.
                </p>

                <ul class="list-disc list-inside text-yellow-700 space-y-1 text-sm sm:text-base mb-6">
                    <li>Renseignez les champs <span class="font-mono">NIF</span> et <span class="font-mono">STAT</span>.
                    </li>
                    <li>Vérifiez l’exactitude des données fournies.</li>
                    <li>Soumettez votre demande et attendez l’email de confirmation.</li>
                </ul>

                <div class="flex justify-center mb-6">
                    <ChevronDownIcon class="w-8 h-8 sm:w-10 sm:h-10 text-yellow-500 animate-bounce"
                        aria-hidden="true" />
                </div>

                <p class="text-yellow-700 text-center font-medium text-base sm:text-lg mb-8">
                    Veuillez compléter les champs obligatoires
                    <span class="font-mono">NIF</span> et
                    <span class="font-mono">STAT</span> pour activer votre compte.
                </p>

                <p class="text-center mb-8">
                    <a href="/aide/validation" target="_blank" rel="noopener noreferrer"
                        class="text-yellow-800 underline hover:text-yellow-600 transition-colors text-sm sm:text-base">
                        Besoin d’aide ? Consultez notre FAQ.
                    </a>
                </p>

                <button type="button" @click="scrollToForm"
                    class="cursor-pointer block mx-auto bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold py-3 px-6 sm:px-10 text-sm sm:text-base rounded-full shadow-md transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-yellow-300 animate-pulse-yellow">
                    Soumettre mes informations
                </button>
            </div>
        </div>
    </div>

    <div class="w-full mt-10 px-0 sm:px-6 lg:px-8 py-5">
        <FormValidate ref="formRef" :ecole="props.ecole" :categories="props.categories" />
    </div>
</template>


<style scoped>
@keyframes fade-slide {
    0% {
        opacity: 0;
        transform: translateY(12px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-slide {
    animation: fade-slide 0.5s ease-out forwards;
}

@keyframes pulse {

    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(202, 138, 4, 0.5);
    }

    50% {
        box-shadow: 0 0 15px 5px rgba(202, 138, 4, 0.8);
    }
}

.animate-pulse-yellow {
    animation: pulse 2s infinite;
}
</style>
