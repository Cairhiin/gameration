<script setup>
import GameCard from '@/Components/Custom/GameCard.vue';

defineProps({
    games: Object
});
</script>

<template>
    <ul>
        <li v-for="game in games.data" :key="game.id" class="my-2 rounded-md odd:bg-darkVariant/25 even:bg-darkVariant/25 hover:bg-lightVariant/15 group
        border border-darkVariant shadow-dark-sm">
            <game-card :game="game" />
        </li>
    </ul>
</template>
