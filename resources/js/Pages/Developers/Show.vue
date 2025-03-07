<script lang="ts" setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
import GameList from '@/Components/Custom/GameList.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameInfoCard from '@/Components/Custom/GameInfoCard.vue';
import type { Data, Developer, Game } from '@/Types';
import { computed, type PropType } from 'vue';

const { developer, games } = defineProps({
    developer: Object as PropType<Developer>,
    games: Object as PropType<Data<Game>>
});

const sortedGamesByRating = computed(() => {
    return [...games.data].sort((a, b) => b.avg_rating - a.avg_rating);
});
</script>

<template>
    <app-layout :title="developer.name">
        <section class="">
            <div id="developer__info" class="grid grid-cols-4 gap-8 text-sm mb-6">

                <game-info-card heading="City" :value="developer.city" icon="fa-city" :precision="0" />
                <game-info-card heading="Country" :value="developer.country" icon="fa-flag" :precision="0" />
                <game-info-card heading="Founded" :value="developer.year" icon="fa-calendar" :precision="0" />
                <game-info-card heading="Average Rating" :value="developer.avg_rating" icon="fa-gauge-simple"
                    :precision="1" />
            </div>

            <div class="article-header relative overflow-hidden">
                <div class="py-4">
                    <h3 class="relative font-bold uppercase text-5xl text-light">{{ developer.name }}</h3>
                </div>
            </div>
        </section>

        <!-- Games -->
        <section class="backdrop-blur-sm">
            <game-list v-if="games.data.length" :games="sortedGamesByRating" />
            <div v-else>No games by this developer.</div>
        </section>

    </app-layout>
</template>
