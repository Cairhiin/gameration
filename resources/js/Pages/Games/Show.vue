<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import image from '../../../images/missing_image_light.png';
import Tag from '@/Components/Custom/Tag.vue';
import Rating from '@/Components/Custom/Rating.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const page = usePage();

const { game, rating } = defineProps({
    game: Object,
    rating: Number
});

console.log(page.props.auth.user.role.name)
const updateRating = (value) => {
    router.post(`/games/${game.id}/rate`, { rating: value * 5 / 100 }, { only: ['game'] });
}
</script>

<template>
    <AppLayout :title="game.name">
        <article class="rounded-xl">
            <div class="p-8 bg-darkVariant/50 rounded-t-xl">
                <h3 class="font-bold uppercase text-2xl text-lightVariant">{{ game.name }}</h3>
                <div class="flex gap-2 my-2"><template v-for="genre in game.genres" :key="genre.id">
                        <tag size="small">{{ genre.name }}</tag>
                    </template>
                </div>
            </div>
            <div class="flex justify-between gap-12 p-8 bg-darkVariant/25">
                <div class="basis-3/4">
                    <div>{{ game.description }}</div>

                </div>
                <div class="basis-1/4">
                    <img :src="game.image ? `/storage/${game.image}` : image" :alt="game.name"
                        class="object-cover w-full">
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Developer</h4>
                    <p>{{ game.developer.name }}</p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Publisher</h4>
                    <p>{{ game.publisher.name }}</p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Release Date</h4>
                    <p>{{ new Date(game.released_at).toLocaleDateString() }}</p>
                </div>
            </div>
            <div class="bg-darkVariant/25 p-4"
                v-if="page.props.auth.user.role.name.toLowerCase() === 'admin' || page.props.auth.user.role.name.toLowerCase() === 'moderator'">
                <div class="flex justify-end gap-4">
                    <primary-button>
                        <Link :href="`/games/${game.id}/edit`">Edit</Link>
                    </primary-button>
                    <danger-button>
                        <Link :href="`/games/${game.id}/delete`">Delete</Link>
                    </danger-button>
                </div>
            </div>
            <div class="flex justify-between bg-darkVariant/50 px-8 py-4 rounded-b-xl">
                <div class="font-bold">{{ game.avg_rating ?? '-' }} ({{ game.rating_count }})</div>
                <div class="">
                    <rating :value="rating" @update-rating="updateRating" size="text-2xl" />
                </div>
            </div>
        </article>
    </AppLayout>
</template>
