<script setup>
import { router, usePage } from '@inertiajs/vue3';
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import AdminBar from '@/Components/Custom/AdminBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

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
            <game-list :games="games" v-if="games.length" />
            <p v-else>No games in this genre</p>
        </section>

        <!-- Pagination -->
        <pagination :links="games?.links" @change-page="onChangePage" />

        <!-- Admin Edit Section -->
        <admin-bar type="genres" :resource="genre" v-if="page.props.auth.user.role.name === 'Admin'"
            :user="page.props.auth.user" />

    </app-layout>
</template>
