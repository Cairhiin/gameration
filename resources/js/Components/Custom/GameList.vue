<script setup>
/**
 * A component to display a list of games.
 *
 * @prop {object} games - An object containing the games to display.
 * The object should have a `data` property containing the games
 * and a `links` property containing the pagination links.
 *
 * @example
 * <game-list :games="games" />
 */
import GameCard from '@/Components/Custom/GameCard.vue';
import { computed } from 'vue';

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
    <ul>
        <li v-for="game in games" :key="game.id">
            <game-card :game="game" />
        </li>
    </ul>
</template>
