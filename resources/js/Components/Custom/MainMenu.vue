<script lang="ts" setup>
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

const isGameDropDownShowing = ref<boolean>(false);
const isBookDropDownShowing = ref<boolean>(false);
</script>

<template>
    <nav aria-label="Main Menu">
        <ul class="text-light font-bold flex justify-center gap-8">

            <!-- Games -->
            <li class="relative" @mouseenter="isGameDropDownShowing = true" @mouseleave="isGameDropDownShowing = false"
                :aria-expanded="isGameDropDownShowing">
                <div class="flex items-center gap-2 cursor-pointer hover:text-dark-highlight-variant">Games
                    <i class="fa-solid fa-chevron-down ease-in-out transition-all duration-300"
                        :class="{ 'rotate-180': isGameDropDownShowing }"></i>
                </div>
                <dropdown-menu :show="isGameDropDownShowing">
                    <dropdown-link :href="route('games.index')" icon="fa-solid fa-list">
                        <template #header>All</template>
                        <template #subheader>A list of all games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index', { sortBy: 'released_at' })" icon="fa-solid fa-gamepad">
                        <template #header>Newest</template>
                        <template #subheader>Check out new games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index', { sortBy: 'popular' })"
                        icon="fa-solid fa-arrow-trend-up">
                        <template #header>Popular </template>
                        <template #subheader>This year's best rated games</template>
                    </dropdown-link>
                    <dropdown-link :href="route('games.index', { sortBy: 'avg_rating' })" icon="fa-solid fa-star">
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

            <!-- Books -->
            <li class="relative" @mouseenter="isBookDropDownShowing = true" @mouseleave="isBookDropDownShowing = false"
                :aria-expanded="isBookDropDownShowing">
                <div class="flex items-center gap-2 cursor-pointer hover:text-dark-highlight-variant">Books
                    <i class="fa-solid fa-chevron-down ease-in-out transition-all duration-300"
                        :class="{ 'rotate-180': isBookDropDownShowing }"></i>
                </div>
                <dropdown-menu :show="isBookDropDownShowing">
                    <dropdown-link :href="route('books.index')" icon="fa-solid fa-list">
                        <template #header>All</template>
                        <template #subheader>A list of all books</template>
                    </dropdown-link>
                    <dropdown-link :href="route('books.index', { sortBy: 'published_at' })" icon="fa-solid fa-gamepad">
                        <template #header>Newest</template>
                        <template #subheader>Check out new books</template>
                    </dropdown-link>
                    <dropdown-link :href="route('books.index', { sortBy: 'trending' })"
                        icon="fa-solid fa-arrow-trend-up">
                        <template #header>Trending </template>
                        <template #subheader>This year's best rated books</template>
                    </dropdown-link>
                    <dropdown-link :href="route('books.index', { sortBy: 'avg_rating' })" icon="fa-solid fa-star">
                        <template #header>Highest Rated </template>
                        <template #subheader>Must read books of all time</template>
                    </dropdown-link>
                    <template v-if="isModerator">
                        <div class="border-b border-lightVariant mx-4 my-2">
                        </div>
                        <dropdown-link icon="fa-solid fa-plus" :href="route('books.create')">
                            <template #header>New Book </template>
                            <template #subheader>Add a new book to the database</template>
                        </dropdown-link>
                    </template>
                </dropdown-menu>
            </li>
            <li>
                <nav-link :href="route('genres.index')" :active="route().current('genres.index')">Genres
                </nav-link>
            </li>
            <li v-if="isModerator">
                <nav-link :href="route('admin.index')" :active="route().current('admin.index')">
                    Admin
                </nav-link>
            </li>
        </ul>
    </nav>
</template>
