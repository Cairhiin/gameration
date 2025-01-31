<script lang="ts" setup>
import GameCard from '@/Components/Custom/GameCard.vue';
import GameCardHorizontal from '@/Components/Custom/GameCardHorizontal.vue';
import image from '../../../images/missing_image_light.png';
import type { PropType } from 'vue';
import type { Game } from '@/Types';
import { dateOptions } from '@/Utils';

const { games, layout } = defineProps({
    games: Object as PropType<Game[]>,
    layout: {
        type: String,
        default: 'grid'
    }
});
</script>

<template>
    <ul v-if="layout === 'grid'"
        class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
        <li v-for="game in games" :key="game.id">
            <GameCardHorizontal :game="game">
                <template #rating>
                    {{ (game.avg_rating ?? 0.0).toFixed(1) }}
                </template>
                <template #image>
                    <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name"
                        class="object-cover w-full group-hover:scale-125 transition-all h-40">
                </template>
                <template #title>
                    <h3 class="font-bold text-default text-pretty">{{ game.name }}</h3>
                </template>
                <template #content>
                    <p>{{ game.description }}</p>
                </template>
                <template #release-date>
                    <p>{{ new Date(game.released_at).toLocaleDateString('fi-fi', dateOptions) }}</p>
                </template>
            </GameCardHorizontal>
        </li>
    </ul>

    <ul v-else class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:block">
        <li v-for="game in games" :key="game.id">
            <game-card :game="game" />
        </li>
    </ul>
</template>
