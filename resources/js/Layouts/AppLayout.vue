<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import MainMenu from '@/Components/Custom/MainMenu.vue';
import UserMenu from '@/Components/Custom/UserMenu.vue';
import FlashMessage from '@/Components/Custom/FlashMessage.vue';
import { canModerate } from '@/Utils';

const page = usePage();
const bgColor = ref('bg-transparent');

const user = page.props.auth.user;
const isLoggedIn = !!user;
const isModerator = canModerate(user);

defineProps({
    title: String,
});

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const handleScroll = () => {
    if (window.scrollY > 100) {
        bgColor.value = 'bg-darkVariant/40';
    } else {
        bgColor.value = 'bg-transparent';
    }
}
</script>

<template>
    <div class=" text-light min-h-screen bg-gradient-to-b from-dark via-dark to-dark-variant">

        <Head :title="title" />

        <Banner />

        <header
            class="transition duration-300 ease-in-out sticky top-0 z-50 flex gap-8 justify-between items-center backdrop-blur-sm"
            aria-label="primary" :class="bgColor">
            <div class="flex items-center">
                <h1 class="uppercase font-bold text-3xl py-4 pl-8">Gameration</h1>
                <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" aria-label="secondary">

                    <!-- Main Menu -->
                    <main-menu :isModerator="isModerator" />
                </div>
            </div>

            <!-- User Menu -->
            <user-menu v-if="isLoggedIn" :user="user" />
        </header>

        <slot name="header" />

        <!-- Page Content -->
        <main class="py-8">
            <slot />
        </main>
        <FlashMessage :message="page.props.flash.message" :show="page.props.flash.message !== null" />
    </div>
</template>
