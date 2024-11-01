<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import DropdownLink from '@/Components/Custom/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import NavLinkButton from '@/Components/Custom/NavLinkButton.vue';
import DropdownMenu from '@/Components/Custom/DropdownMenu.vue';

const page = usePage();

const isLoggedIn = page.props.auth.user;
const isModerator = page.props.auth.user.role.name === 'Admin' || page.props.auth.user.role.name === 'Moderator';
const isGameDropDown = ref(false);

defineProps({
    title: String,
});

const logout = () => {
    router.post(route('logout'));
};
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
                    <nav>
                        <ul class="text-lightVariant font-bold flex justify-center gap-8">
                            <li class="relative" @mouseenter="isGameDropDown = true"
                                @mouseleave="isGameDropDown = false">
                                <nav-link class="flex items-center gap-2" :href="route('home')"
                                    :active="route().current('home')">Games <i
                                        class="fa-solid fa-chevron-down ease-in-out transition-all duration-300"
                                        :class="{ 'rotate-180': isGameDropDown }"></i>
                                </nav-link>
                                <dropdown-menu :show="isGameDropDown">
                                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-gamepad">
                                        <template #header>Newest</template>
                                        <template #subheader>Check out new games</template>
                                    </dropdown-link>
                                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-arrow-trend-up">
                                        <template #header>Popular </template>
                                        <template #subheader>This year's best rated games</template>
                                    </dropdown-link>
                                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-star">
                                        <template #header>Highest Rating </template>
                                        <template #subheader>Must play games of all time</template>
                                    </dropdown-link>
                                    <div v-if="isModerator" class="border-b border-lightVariant mx-4">
                                    </div>
                                    <dropdown-link v-if="isModerator" icon="fa-solid fa-plus"
                                        :href="route('games.create')">
                                        <template #header>New Game </template>
                                        <template #subheader>Add a new game to the database</template>
                                    </dropdown-link>
                                </dropdown-menu>
                            </li>
                            <li>
                                <nav-link :href="route('games.index')" :active="route().current('games.index')">Genres
                                </nav-link>
                            </li>
                            <li>
                                <nav-link :href="route('genres.index')" :active="route().current('genres.index')">
                                    Developers
                                </nav-link>
                            </li>
                            <li>
                                <nav-link :href="route('dashboard')" :active="route().current('dashboard')">Publishers
                                </nav-link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="relative flex gap-2 items-center cursor-pointer h-16 pr-8 border-b-2 border-transparent">
                <nav>
                    <ul v-if="isLoggedIn" class="flex items-center gap-2">
                        <li>
                            <i class="fa-solid fa-user"></i>
                            <span class="truncate shrink">{{ page.props.auth.user.username }}</span>
                        </li>
                        <li>
                            <NavLinkButton @click="logout"><i class="fa-solid fa-gauge"></i> Logout</NavLinkButton>
                        </li>
                    </ul>
                    <ul v-else class="flex items-center gap-2">
                        <li class="p-4 hover:bg-lightVariant/25 rounded-t">
                            <nav-link-button :href="route('login')"><i class="fa-solid fa-gauge"></i> Login
                            </nav-link-button>
                        </li>
                        <li class="p-4 hover:bg-lightVariant/25">
                            <nav-link-button :href="route('register')"><i class="fa-solid fa-user"></i> Register
                            </nav-link-button>
                        </li>
                    </ul>
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
