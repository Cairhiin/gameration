<script setup>
import { ref } from 'vue';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/Components/Custom/DropdownMenu.vue';
import DropdownLink from '@/Components/Custom/DropdownLink.vue';

defineProps({
    isModerator: {
        type: Boolean,
        default: false
    }
});

const isGameDropDownShowing = ref(false);
</script>

<template>
    <nav aria-label="Main Menu">
        <ul class=" text-lightVariant font-bold flex justify-center gap-8">
            <li class="relative" @mouseenter="isGameDropDownShowing = true" @mouseleave="isGameDropDownShowing = false"
                :aria-expanded="isGameDropDownShowing">
                <div class="flex items-center gap-2 cursor-pointer">Games
                    <i class="fa-solid fa-chevron-down ease-in-out transition-all duration-300"
                        :class="{ 'rotate-180': isGameDropDownShowing }"></i>
                </div>
                <dropdown-menu :show="isGameDropDownShowing">
                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-list">
                        <template #header>All</template>
                        <template #subheader>A list of all games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-gamepad">
                        <template #header>Newest</template>
                        <template #subheader>Check out new games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-arrow-trend-up">
                        <template #header>Popular </template>
                        <template #subheader>This year's best rated games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-star">
                        <template #header>Highest Rated </template>
                        <template #subheader>Must play games of all time</template>
                    </dropdown-link>
                    <template v-if="isModerator">
                        <div class="border-b border-lightVariant mx-4 my-2">
                        </div>
                        <dropdown-link icon="fa-solid fa-plus" :href="route('games.create')">
                            <template #header>New Game </template>
                            <template #subheader>Add a new game to the database</template>
                        </dropdown-link>
                    </template>
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
</template>
