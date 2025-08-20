<script setup>
import Footer from '@/components/app/user/Footer.vue';
import Nav from '@/components/app/user/Nav.vue';


</script>

<template>
    <div>
        <Nav />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
