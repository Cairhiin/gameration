<script setup>
const { genre } = defineProps({
    genre: Object
})
console.log(genre)
</script>

<template>
    <h3>{{ genre.name }}</h3>
    <div v-for="game in genre.games" :key="game.id">{{ game.name }}</div>
</template>
