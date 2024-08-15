<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const { genre } = defineProps({
    genre: Object
})
</script>

<template>
    <app-layout :title="genre.name">
        <h3>{{ genre.name }}</h3>
        <div v-for="game in genre.games" :key="game.id">{{ game.name }}</div>
    </app-layout>
</template>
