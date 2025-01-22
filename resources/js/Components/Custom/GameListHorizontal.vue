<script setup>
import { computed } from 'vue';
import image from '../../../images/missing_image_light.png';
import GameCardHorizontal from './GameCardHorizontal.vue';

const props = defineProps({
    games: Object
});
const games = computed(() => {
    // If the `data` property exists, use it. Otherwise, use the entire object.
    // This is because the `games` prop might be an instance of Laravel's
    // `LengthAwarePaginator` class, which has a `data` property.
    return props.games.data ?? props.games;
});
</script>

<template>
    <div class="grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6">
        <GameCardHorizontal v-for="game in games" :key="game.id" :game="game">
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
        </GameCardHorizontal>
    </div>
</template>
