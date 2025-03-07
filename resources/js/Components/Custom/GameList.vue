<script lang="ts" setup>
import GameCard from '@/Components/Custom/GameCard.vue';
import GameCardHorizontal from '@/Components/Custom/GameCardHorizontal.vue';
import image from '../../../images/missing_image_light.png';
import type { PropType } from 'vue';
import type { Game } from '@/Types';
import { dateOptions } from '@/Utils';
import SkeletonGameCardHorizontal from './SkeletonGameCardHorizontal.vue';

const { games, layout } = defineProps({
    games: Object as PropType<Game[]>,
    layout: {
        type: String,
        default: 'grid'
    },
    isLoading: Boolean
});
</script>

<template>
    <Transition enter-active-class="transition ease-in duration-500 delay-500" enter-from-class="translate-x-16"
        enter-to-class="translate-x-0" leave-active-class="transition ease-in duration-500"
        leave-from-class="translate-x-0" leave-to-class="translate-x-16">
        <ul v-if="layout === 'grid'"
            class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
            <li v-for="game in games" :key="game.id">
                <SkeletonGameCardHorizontal v-if="isLoading" />
                <GameCardHorizontal :game="game" v-else>
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
    </Transition>

    <Transition enter-active-class="transition ease-in duration-500 delay-500" enter-from-class="translate-x-16"
        enter-to-class="translate-x-0" leave-active-class="transition ease-in duration-500"
        leave-from-class="translate-x-0" leave-to-class="translate-x-16">
        <ul v-if="layout !== 'grid'">
            <li v-for="game in games" :key="game.id">
                <game-card :game="game" />
            </li>
        </ul>
    </Transition>
</template>
