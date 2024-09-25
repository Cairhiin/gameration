<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const { genre, games } = defineProps({
    genre: Object,
    games: Object
});

const onChangePage = (page) => {
    router.get(page, { sortBy: sortBy.value, sortOrder: sortOrder.value }, { preserveState: true });
}
</script>

<template>
    <app-layout :title="genre.name">
        <sub-header level="h3">{{ genre.name }}</sub-header>

        <!-- Games by Genre -->
        <section class="backdrop-blur-sm">
            <game-list :games="games" />
        </section>

        <!-- Pagination -->
        <aside>
            <pagination :links="games?.links" @change-page="onChangePage" />
        </aside>
    </app-layout>
</template>
