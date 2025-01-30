<script lang="ts" setup>
import { router, usePage } from '@inertiajs/vue3';
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import Pagination from '@/Components/Custom/Pagination.vue';
import AdminBar from '@/Components/Custom/AdminBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { InertiaPageProps } from '@/Types/inertia';
import type { PropType } from 'vue';
import type { Genre, Data, Game } from '@/Types';
import { isModerator } from '@/Utils';

const { props } = usePage<InertiaPageProps>();

const isUserModerator: boolean = isModerator(props.auth.user);

const { genre, games } = defineProps({
    genre: Object as PropType<Genre>,
    games: Object as PropType<Data<Game>>,
});

const onChangePage = (page): void => {
    router.get(page, {}, { preserveState: true });
}
</script>

<template>
    <app-layout :title="genre.name">
        <sub-header level="h3">{{ genre.name }}</sub-header>

        <!-- Games by Genre -->
        <section class="backdrop-blur-sm">
            <game-list :games="games.data" v-if="games.data.length" />
            <p v-else>No games in this genre</p>
        </section>

        <!-- Pagination -->
        <pagination :links="games?.links" @change-page="onChangePage" />

        <!-- Admin Edit Section -->
        <admin-bar type="genres" :resource="genre" v-if="isUserModerator" :user="props.auth.user" />

    </app-layout>
</template>
