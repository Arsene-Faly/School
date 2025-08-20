<script setup>
import Section from '@/components/app/user/section/Section.vue';
import SectionDefault from '@/components/app/user/section/SectionDefault.vue';
import UserLayout from '@/layouts/User/UserLayout.vue';
import Message from './page/Message.vue';

const props = defineProps({
  ecole: {
    type: Object,
    default: null,
  },
})

</script>

<template>
    <UserLayout>
        <SectionDefault title="Page d'attente de validation">
        </SectionDefault>

        <Section name="Attente validation de propriétaire"
            description="Votre demande pour devenir propriétaire est en attente de validation par un administrateur. Vous recevrez une notification une fois la validation effectuée ou refusé.">
            <Message :ecole="props.ecole"/>
        </Section>


    </UserLayout>
</template>
