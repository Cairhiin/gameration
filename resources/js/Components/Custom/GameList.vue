<script setup>
import GameCard from '@/Components/Custom/GameCard.vue';

defineProps({
    games: Object
});
</script>

<template>
    <ul>
        <li v-for="game in games.data" :key="game.id" class="my-2 rounded-md bg-dark/60 hover:bg-lightVariant/15 group
        border border-darkVariant shadow-dark-sm">
            <game-card :game="game" />
        </li>
    </ul>
</template>
