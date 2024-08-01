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
    <div class="bg-dark text-light ">

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
        <header class="bg-highlight/25">
            <div class="py-2 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" aria-label="secondary">
                <nav class="uppercase text-lightVariant font-bold flex gap-4">
                    <NavLink href="route('home')" :active="route().current('home')">Home</NavLink>
                    <NavLink :href="route('games.index')" :active="route().current('games.index')">Games</NavLink>
                    <NavLink :href="route('genres.index')" :active="route().current('genres.index')">Genres</NavLink>
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="ms-4">Dashboard
                    </NavLink>
                </nav>
            </div>
        </header>
        <slot name="header" />
        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
