<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import NavLinkButton from '@/Components/Custom/NavLinkButton.vue';
import DropdownLink from './DropdownLink.vue';
import DropdownMenu from './DropdownMenu.vue';

const isUserMenuShowing = ref(false);

const { user } = defineProps({
    user: Object
});

const isLoggedIn = computed(() => {
    return !!user;
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <nav class="relative flex gap-2 items-center cursor-pointer h-16 pr-8 border-b-2 border-transparent">
        <ul v-if="isLoggedIn" class="flex items-center gap-4">
            <li>
                <NavLinkButton @click="logout" icon="fa-solid fa-right-from-bracket">Logout
                </NavLinkButton>
            </li>
            <li class="relative flex items-center gap-2" @mouseenter="isUserMenuShowing = true"
                @mouseleave="isUserMenuShowing = false">
                <i class="fa-solid fa-user"></i>
                <span class="truncate shrink">{{ user.username }}</span>
                <dropdown-menu :show="isUserMenuShowing">
                    <dropdown-link :href="route('dashboard')" icon="fa-solid fa-gauge">
                        <template #header>Dashboard</template>
                        <template #subheader>An at a glance view of your data</template>
                    </dropdown-link>
                    <dropdown-link :href="route('profile.show')" icon="fa-solid fa-user">
                        <template #header>Profile</template>
                        <template #subheader>Your personal information</template>
                    </dropdown-link>
                    <dropdown-link :href="route('profile.ratings.index')" icon="fa-solid fa-user">
                        <template #header>Ratings</template>
                        <template #subheader>An overview of your ratings</template>
                    </dropdown-link>
                </dropdown-menu>
            </li>
        </ul>
        <ul v-else class="flex items-center gap-2">
            <li class="p-4 hover:bg-lightVariant/25 rounded-t">
                <nav-link-button :href="route('login')" icon="fa-solid fa-right-to-bracket">Login
                </nav-link-button>
            </li>
            <li class="p-4 hover:bg-lightVariant/25">
                <nav-link-button :href="route('register')" icon="fa-solid fa-user">Register
                </nav-link-button>
            </li>
        </ul>
    </nav>
</template>
