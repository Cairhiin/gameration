<script setup>
import { Link } from '@inertiajs/vue3';
import image from '../../../images/missing_image_light.png';
import AppLayout from '@/Layouts/AppLayout.vue';

const { games } = defineProps({
    games: Object
});
console.log(games);
</script>

<template>
    <AppLayout title="Games">
        <ul>
            <li v-for="game in  games.data " :key="game.id" class="last:border-0 border-b border-light/25 py-2">
                <Link :href="route('games.show', game)" class="flex justify-between">
                <div class="flex gap-4 items-center">
                    <img :src="`storage/${game.image}` ?? image" :alt="game.name" class="object-cover w-30 h-12">
                    <div>
                        <div>{{ game.name }}</div>
                        <div>{{ game.developer?.name }} {{ game.released_at }}</div>
                    </div>
                </div>
                <span>{{
                game.rating ??
                '-' }} ({{ game.rating_count
                    }})</span>
                </Link>
            </li>
        </ul>
        <ul class="flex gap-2" v-if="games?.links?.length > 3">
            <li v-for=" link  in  games.links" :key="link.label" class="border rounded px-3 py-1">
                <Link v-if="link.url" :href="link.url" v-html="link.label">
                </Link>
                <span v-else v-html="link.label"></span>
            </li>
        </ul>
    </AppLayout>
</template>
