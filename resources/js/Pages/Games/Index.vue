<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import GameList from '@/Components/Custom/GameList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';
import LayoutSelector from '@/Components/Custom/LayoutSelector.vue';

const { games } = defineProps({
    games: Object
});

const sortBy = ref('released_at');
const sortOrder = ref('desc');
const layout = ref('grid');

const setLayout = (value) => {
    layout.value = value;
}

const setSortBy = (value) => {
    if (sortBy.value === value) {
        sortOrder.value = sortOrder.value === 'desc' ? 'asc' : 'desc';
    } else if (value === 'name') {
        sortOrder.value = 'asc'
    } else {
        sortOrder.value = 'desc';
    }

    sortBy.value = value

    router.get(route('games.index'), { sortBy: sortBy.value, sortOrder: sortOrder.value }, {
        preserveState: true,
    });
}

const onChangePage = (page) => {
    router.get(page, { sortBy: sortBy.value, sortOrder: sortOrder.value }, { preserveState: true });
}
</script>

<template>
    <app-layout title="Games">

        <!-- Sort By -->
        <nav class="flex justify-between ml-4">
            <layout-selector @layout="setLayout" />
            <ul class="flex justify-end gap-4 p-2 text-sm uppercase border-b-2 border-dark/50">
                <li @click="setSortBy('name')" class="cursor-pointer">
                    <primary-button variant="invert" class="flex gap-2 items-center">Alphabetical
                        <i class="fa-solid fa-chevron-down text-xs text-dark-highlight-variant transition-all" :class="{
                            'rotate-180': sortOrder === 'asc', 'text-transparent': sortBy !== 'name'
                        }"></i>
                    </primary-button>
                </li>
                <li @click="setSortBy('avg_rating')" class="cursor-pointer">
                    <primary-button variant="invert" class="flex gap-2 items-center">Rating
                        <i class="fa-solid fa-chevron-down text-xs text-dark-highlight-variant transition-all" :class="{
                            'rotate-180': sortOrder === 'asc', 'text-transparent': sortBy !== 'avg_rating'
                        }"></i>
                    </primary-button>
                </li>
                <li @click="setSortBy('released_at')" class="cursor-pointer">
                    <primary-button variant="invert" class="flex gap-2 items-center">Release Date
                        <i class="fa-solid fa-chevron-down text-xs text-dark-highlight-variant transition-all" :class="{
                            'rotate-180': sortOrder === 'asc', 'text-transparent': sortBy !== 'released_at'
                        }"></i>
                    </primary-button>
                </li>
            </ul>
        </nav>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <!-- <game-list :games="games" /> -->
            <game-list :games="games" :layout="layout" />
        </section>

        <!-- Pagination -->
        <aside>
            <pagination :links="games.links" @change-page="onChangePage" />
        </aside>

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
