<script setup>
defineProps({
    games: Object
})
</script>

<template>
    <ul>
        <li v-for="game in games" :key="game.id">
            {{ game.name }}
        </li>
    </ul>
</template>
