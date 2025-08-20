<script setup>

import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import { ref } from 'vue';

const openItem = ref(null)

const toggle = (id) => {
    openItem.value = openItem.value === id ? null : id
}

const faqs = [
    {
        category: 'Général',
        items: [
            {
                id: 'general_0',
                question: 'Qu’est-ce que la plateforme MonAvenirEdu ?',
                answer:
                    'MonAvenirEdu est une plateforme permettant à tous de rechercher des écoles, formations et activités éducatives à Madagascar. Elle facilite la connexion entre étudiants, parents et établissements.',
            },
            {
                id: 'general_1',
                question: 'Qui peut utiliser cette plateforme ?',
                answer:
                    'Tout le monde ! Les visiteurs peuvent rechercher librement, les utilisateurs peuvent créer un compte pour enregistrer leurs recherches, et les écoles peuvent créer un profil pour présenter leurs offres.',
            },
        ],
    },
    {
        category: 'Comptes & Connexion',
        items: [
            {
                id: 'account_0',
                question: 'Comment créer un compte sur la plateforme ?',
                answer:
                    'Il suffit de s’inscrire avec un email valide. Un lien de confirmation vous sera envoyé par email pour activer votre compte.',
            },
            {
                id: 'account_1',
                question: 'Quels sont les rôles disponibles sur la plateforme ?',
                answer:
                    'Il existe deux rôles : "Utilisateur" (pour rechercher et enregistrer des écoles/activités) et "Propriétaire d’école" (pour gérer un établissement).',
            },
            {
                id: 'account_2',
                question: 'Comment devenir propriétaire d’une école ?',
                answer:
                    'Lors de la création de compte, vous pouvez choisir le rôle "Propriétaire d’école". Vous devrez ensuite remplir un formulaire avec les informations officielles de votre établissement pour vérification manuelle.',
            },
        ],
    },
    {
        category: 'Écoles & Formations',
        items: [
            {
                id: 'ecole_0',
                question: 'Comment rechercher une école ou une formation ?',
                answer:
                    'Utilisez la barre de recherche en page d’accueil ou dans le menu. Vous pouvez filtrer par ville, type d’école, niveau ou domaine de formation.',
            },
            {
                id: 'ecole_1',
                question: 'Comment les écoles sont-elles validées ?',
                answer:
                    'Chaque école est soumise à une validation manuelle après vérification des informations fournies dans le formulaire par le propriétaire.',
            },
            {
                id: 'ecole_2',
                question: 'Puis-je voir les activités proposées par une école ?',
                answer:
                    'Oui, chaque fiche d’école présente les activités extra-scolaires proposées (clubs, sports, événements, etc.).',
            },
        ],
    },
    {
        category: 'Support & Contact',
        items: [
            {
                id: 'support_0',
                question: 'Je n’ai pas reçu l’email de confirmation, que faire ?',
                answer:
                    'Vérifiez dans vos spams. Si vous ne l’avez toujours pas reçu, vous pouvez demander un nouvel envoi depuis la page de connexion.',
            },
            {
                id: 'support_1',
                question: 'Comment contacter l’équipe de MonAvenirEdu ?',
                answer:
                    'Utilisez le formulaire de contact disponible en bas du site ou envoyez-nous un message à support@monaveniredu.mg.',
            },
        ],
    },
]
</script>

<template>
    <UserLayout>
        <SectionDefault title="Foire Aux Questions" />

        <Section name="FAQ" description="Notre plateforme vous permet d'explorer les écoles les mieux notées.">
            <div class="mx-auto px-4 py-8">
                <div v-for="section in faqs" :key="section.category" class="mb-10">
                    <h2 class="text-2xl font-bold text-indigo-800 border-b-2 border-indigo-600 pb-2 mb-4">
                        {{ section.category }}
                    </h2>
                    <div class="space-y-4">
                        <div v-for="item in section.items" :key="item.id" class="border-b border-gray-300">
                            <button @click="toggle(item.id)"
                                class="w-full flex justify-between items-center py-4 text-left focus:outline-none">
                                <span class="text-lg font-medium text-gray-800">{{ item.question }}</span>
                                <svg :class="{ 'rotate-180': openItem === item.id }"
                                    class="w-5 h-5 text-indigo-600 transform transition-transform duration-200"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <transition enter-active-class="transition duration-200 ease-out"
                                enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                                <div v-if="openItem === item.id" class="bg-gray-100 p-4 rounded text-gray-700">
                                    {{ item.answer }}
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </Section>
    </UserLayout>
</template>
