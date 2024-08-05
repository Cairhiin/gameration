<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import image from '../../../images/missing_image_light.png';
import Tag from '@/Components/Custom/Tag.vue';
import Rating from '@/Components/Custom/Rating.vue';

const { game } = defineProps({
    game: Object
});
</script>

<template>
    <AppLayout :title="game.name">
        <article>
            <h3 class="font-bold uppercase text-2xl text-lightVariant">{{ game.name }}</h3>
            <div class="flex gap-2 my-4"><template v-for="genre in game.genres" :key="genre.id">
                    <tag>{{ genre.name }}</tag>
                </template>
            </div>
            <div class="flex gap-8">
                <div class="basis-2/3">
                    <div>{{ game.description }}</div>
                    <div class="flex justify-between mt-4">
                        <div>{{ game.rating ?? '-' }} ({{ game.rating_count }})</div>
                        <div>
                            <rating :value="game.rating" />
                        </div>
                    </div>
                </div>
                <div class="basis-1/3">
                    <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name" class="object-cover">
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Developer</h4>
                    <p>{{ game.developer.name }}</p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Publisher</h4>
                    <p>{{ game.publisher.name }}</p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Release Date</h4>
                    <p>{{ new Date(game.released_at).toLocaleDateString() }}</p>
                </div>
            </div>
        </article>
    </AppLayout>
</template>
