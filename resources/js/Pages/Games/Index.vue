<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import GameList from '@/Components/Custom/GameList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import AdminCreateSection from '@/Components/Custom/AdminCreateSection.vue';

const { games } = defineProps({
    games: Object
});

const sortBy = ref('released_at');
const sortOrder = ref('desc');

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
        <nav>
            <ul class="flex justify-end gap-4 bg-highlight p-2 text-sm uppercase border-b-2 border-dark/50">
                <li @click="setSortBy('name')" class="flex gap-2 items-center cursor-pointer">Alphabetical
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'name'
                }"></i>
                </li>
                <li @click="setSortBy('avg_rating')" class="flex gap-2 items-center cursor-pointer">Rating
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'avg_rating'
                }"></i>
                </li>
                <li @click="setSortBy('released_at')" class="flex gap-2 items-center cursor-pointer">
                    Release Date
                    <i class="fa-solid fa-chevron-down text-xs text-lightVariant transition-all" :class="{
                    'rotate-180': sortOrder === 'asc', 'text-lightVariant/0': sortBy !== 'released_at'
                }"></i>
                </li>
            </ul>
        </nav>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <game-list :games="games" />
        </section>

        <!-- Pagination -->
        <aside>
            <pagination :links="games.links" @change-page="onChangePage" />
        </aside>

        <!-- Admin Create Section -->
        <admin-create-section />
    </app-layout>
</template>
