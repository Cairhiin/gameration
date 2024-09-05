<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const page = usePage();

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="bg-dark text-light min-h-screen background-image">

        <Head :title="title" />

        <Banner />

        <nav class="flex gap-8 justify-between items-center bg-highlight px-8 py-4" aria-label="primary">
            <h1 class="uppercase font-bold text-3xl">Gameration</h1>
            <div class="basis-1/3">
                <input type="text" placeholder="Search" class="w-full rounded-3xl text-dark" />
            </div>
            <div>
                <div class="flex gap-2 items-center">
                    <i class="fa-solid fa-user"></i>
                    <Link :href="route('dashboard')">{{ page.props.auth.user.name }}</Link>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-highlightDark">
            <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" aria-label="secondary">
                <nav class="uppercase text-lightVariant font-bold flex justify-center gap-8">
                    <NavLink href="route('home')" :active="route().current('home')">Home</NavLink>
                    <NavLink :href="route('games.index')" :active="route().current('games.index')">Games</NavLink>
                    <NavLink :href="route('genres.index')" :active="route().current('genres.index')">Genres</NavLink>
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard
                    </NavLink>
                </nav>
            </div>
        </header>

        <slot name="header" />
        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 leading-9 text-lg">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.background-image {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' version='1.1' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns:svgjs='http://svgjs.dev/svgjs' width='1440' height='1000' preserveAspectRatio='none' viewBox='0 0 1440 1000'%3e%3cg mask='url(%26quot%3b%23SvgjsMask1057%26quot%3b)' fill='none'%3e%3crect width='1440' height='1000' x='0' y='0' fill='url(%26quot%3b%23SvgjsLinearGradient1058%26quot%3b)'%3e%3c/rect%3e%3cpath d='M1440 0L782.93 0L1440 290.08z' fill='rgba(255%2c 255%2c 255%2c .1)'%3e%3c/path%3e%3cpath d='M782.93 0L1440 290.08L1440 352.37L763.55 0z' fill='rgba(255%2c 255%2c 255%2c .075)'%3e%3c/path%3e%3cpath d='M763.55 0L1440 352.37L1440 453.31L522.03 0z' fill='rgba(255%2c 255%2c 255%2c .05)'%3e%3c/path%3e%3cpath d='M522.03 0L1440 453.31L1440 807.8399999999999L172.13 0z' fill='rgba(255%2c 255%2c 255%2c .025)'%3e%3c/path%3e%3cpath d='M0 1000L659.42 1000L0 713.06z' fill='rgba(0%2c 0%2c 0%2c .1)'%3e%3c/path%3e%3cpath d='M0 713.06L659.42 1000L737.62 1000L0 628.26z' fill='rgba(0%2c 0%2c 0%2c .075)'%3e%3c/path%3e%3cpath d='M0 628.26L737.62 1000L780.28 1000L0 438.64z' fill='rgba(0%2c 0%2c 0%2c .05)'%3e%3c/path%3e%3cpath d='M0 438.64L780.28 1000L1028.6599999999999 1000L0 372.61z' fill='rgba(0%2c 0%2c 0%2c .025)'%3e%3c/path%3e%3c/g%3e%3cdefs%3e%3cmask id='SvgjsMask1057'%3e%3crect width='1440' height='1000' fill='white'%3e%3c/rect%3e%3c/mask%3e%3clinearGradient x1='92.36%25' y1='-11%25' x2='7.64%25' y2='111%25' gradientUnits='userSpaceOnUse' id='SvgjsLinearGradient1058'%3e%3cstop stop-color='%230e2a47' offset='0'%3e%3c/stop%3e%3cstop stop-color='rgba(33%2c 42%2c 49%2c 1)' offset='1'%3e%3c/stop%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
