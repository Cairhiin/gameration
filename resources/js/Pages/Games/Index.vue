<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const { games } = defineProps({
    games: Object
})
</script>

<template>
    <AppLayout title="Games">
        <ul>
            <li v-for="game in games.data" :key="game.id">
                <Link :href="route('games.show', game)">{{ game.name }}</Link>
            </li>
        </ul>
        <ul class="flex gap-2">
            <li v-for="link in games.links" :key="link.label" class="border rounded px-3 py-1">
                <Link v-if="link.url" :href="link.url" v-html="link.label">
                </Link>
                <span v-else v-html="link.label"></span>
            </li>
        </ul>
    </AppLayout>
</template>
