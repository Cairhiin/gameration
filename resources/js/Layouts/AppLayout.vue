<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import DropdownLink from '@/Components/Custom/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import NavLinkButton from '@/Components/Custom/NavLinkButton.vue';
import DropdownMenu from '@/Components/Custom/DropdownMenu.vue';
import MainMenu from '@/Components/Custom/MainMenu.vue';
import UserMenu from '@/Components/Custom/UserMenu.vue';

const page = usePage();

const user = page.props.auth.user;
const isLoggedIn = !!user;
const isModerator = page.props.auth.user.role.name === 'Admin' || page.props.auth.user.role.name === 'Moderator';

defineProps({
    title: String,
});
</script>

<template>
    <div class=" text-light min-h-screen bg-gradient-to-b from-dark via-dark to-darkVariant">

        <Head :title="title" />

        <Banner />

        <header class="flex gap-8 justify-between items-center bg-dark" aria-label="primary">
            <div class="flex items-center">
                <h1 class="uppercase font-bold text-3xl py-4 pl-8">Gameration</h1>
                <!-- <div class="basis-1/3 shrink-0">
                <search-input searchType="games" inputStyle="rounded" />
            </div> -->
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
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 leading-9 text-lg">
            <slot />
        </main>
    </div>
</template>
