<script setup>
import { Link } from '@inertiajs/vue3';
import SubHeader from '@/Components/Custom/SubHeader.vue';
import image from '../../../images/missing_image_light.png';

const { game } = defineProps({
    game: Object
});
</script>

<template>
    <Link :href="route('games.show', game)" class="flex justify-between" v-if="game">
    <div class="flex gap-4">
        <div class="overflow-hidden w-24 h-32 rounded-l-md">
            <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name"
                class="object-cover group-hover:scale-125 transition-all w-24 h-32">
        </div>
        <div class="py-2">
            <sub-header level="h4" class="font-bold uppercase text-xl text-lightVariant">{{ game.name
                }}</sub-header>
            <div>{{ game.developer?.name }} ({{ new Date(game.released_at).getFullYear() }})</div>
        </div>
    </div>
    <span class="my-auto px-2 text-xl">{{
        game.avg_rating?.toFixed(1) ??
        '-' }} ({{ game.rating_count
        }})</span>
    </Link>
</template>
