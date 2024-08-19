<script setup>
const { games } = defineProps({
    games: Array
});

console.log(games)
</script>

<template>
    <h2>Games by User</h2>
    <ul>
        <li v-for="game in games.data" :key="game.id">{{ game.name }} ({{ game.users[0].pivot.rating }})</li>
    </ul>
</template>
