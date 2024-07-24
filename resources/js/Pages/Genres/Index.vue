<script setup>
import { capitalize } from '@/Utils/index.ts';

defineProps({
    genres: Array
});
</script>

<template>
    <div>
        <h2>Genres</h2>
        <ul>
            <li v-for="genre in genres" :key="genre.id">
                {{ capitalize(genre.name) }}
            </li>
        </ul>
    </div>
</template>
