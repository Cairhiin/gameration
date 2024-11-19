<script setup>
import GameCard from '@/Components/Custom/GameCard.vue';

defineProps({
    games: Object
});
</script>

<template>
    <ul>
        <li v-for="game in games.data" :key="game.id">
            <game-card :game="game" />
        </li>
    </ul>
</template>
