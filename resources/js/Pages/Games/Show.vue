<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    game: Object
});
</script>

<template>
    <AppLayout :title="game.name">
        <div class="flex">
            <article class="basis-1/2">
                <h3>{{ game.name }}</h3>
                <div><span v-for="genre in game.genres" :key="genre.id">{{ genre.name }}</span></div>
                <div>{{ game.description }}</div>
                <div>{{ game.developer.name }} {{ game.released_at }}</div>
                <div>{{ game.rating ?? '-' }} ({{ game.rating_count }})</div>
            </article>
            <aside class="basis-1/2">
                <img v-if="game.image" :src="game.image" :alt="game.name">
            </aside>
        </div>
    </AppLayout>
</template>
