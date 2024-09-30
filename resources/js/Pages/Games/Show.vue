<script setup>
import { computed, ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import image from '../../../images/missing_image_light.png';
import Tag from '@/Components/Custom/Tag.vue';
import Rating from '@/Components/Custom/Rating.vue';
import AdminBar from '@/Components/Custom/AdminBar.vue';
import barOptions from '@/Utils/barOptions.js';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const page = usePage();

const { game, rating, last_user_ratings } = defineProps({
    game: Object,
    rating: Number,
    last_user_ratings: Array
});

const ratingsByScore = computed(() => {
    const rates = {
        1: 0,
        2: 0,
        3: 0,
        4: 0,
        5: 0,
        6: 0,
        7: 0,
        8: 0,
        9: 0,
        10: 0
    };
    page.props.game.user_ratings.forEach(u => rates[u.rating * 2] += 1);
    return rates;
});

const data = computed(() => {
    return {
        labels: Object.keys(ratingsByScore.value).map(key => key / 2),
        datasets: [
            {
                label: 'Ratings',
                data: Object.values(ratingsByScore.value),
                yAxisId: 'y',
                xAxisId: 'x',
                backgroundColor: 'rgba(179,181,198,0.8)',
                maxBarThickness: 24,
                borderRadius: 4
            }
        ],
    };
});

const lastUserRatings = ref(last_user_ratings);
const loading = ref(false);

const gameImage = computed(() => game.image ? `/storage/${game.image}` : image);

const updateRating = (value) => {
    router.post(`/games/${game.id}/rate`, { rating: value * 5 / 100 }, {
        only: ['game', 'last_user_ratings'], replace: true, preserveState: true,
        onSuccess: (res) => {
            lastUserRatings.value = [...res.props.last_user_ratings.map(r => {
                return {
                    'rating': r.rating, 'user': r.user, 'id': r.game_id + r.user_id
                }
            })];
            loading.value = false;
        },
    });
}
</script>

<template>
    <AppLayout :title="game.name">
        <article class="rounded-xl border border-darkVariant backdrop-blur-sm shadow-dark-sm">
            <div class="article-header relative p-8 rounded-t-xl bg-gradient-to-r from-darkVariant/50 via-highlight/25 to-highlight/50 overflow-hidden"
                :style="{ '--background': `url(${gameImage})` }">
                <h3 class="relative font-bold uppercase text-2xl text-light">{{ game.name }}</h3>
                <div class="relative flex gap-2 my-2">
                    <template v-for="genre in game.genres" :key="genre.id">
                        <tag size="small">{{ genre.name }}</tag>
                    </template>
                </div>
            </div>

            <div class="flex justify-between gap-12 p-8 bg-darkVariant/25">
                <div class="basis-3/4">
                    <div>{{ game.description }}</div>
                </div>
                <div class="basis-1/4">
                    <img :src="gameImage" :alt="game.name" class="object-cover w-full">
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">
                        Developer
                    </h4>
                    <p class="hover:underline">
                        <Link :href="route('developers.show', game.developer)">{{ game.developer.name }}</Link>
                    </p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">
                        Publisher
                    </h4>
                    <p class="hover:underline">
                        <Link :href="route('publishers.show', game.publisher)">{{ game.publisher.name }}</Link>
                    </p>
                    <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Release Date</h4>
                    <p>{{ new Date(game.released_at).toLocaleDateString() }}</p>
                </div>
            </div>

            <!-- Last 10 Ratings -->
            <div class=" bg-darkVariant/25 px-8 border-t border-darkVariant">
                <h3 class="text-lightVariant font-bold uppercase text-sm py-4">Newest Ratings</h3>
                <ul class="py-4">
                    <li v-for="rating in lastUserRatings"
                        :key="rating.game_id ? rating.game_id + rating.rating : rating.id"
                        class="flex gap-2 justify-between items-center">
                        {{
        rating.user?.username
    }}
                        <rating :value="rating.rating" size="text-xl" :rateable="false" />
                    </li>
                </ul>
            </div>

            <!-- Rating Breakdown -->
            <div class=" bg-darkVariant/25 px-8 border-t border-darkVariant">
                <h3 class="text-lightVariant font-bold uppercase text-sm py-4">Rating Breakdown</h3>
                <div class="py-4 h-62 flex justify-left">
                    <Bar :data="data" :options="barOptions" class="bg-dark p-8 rounded-lg" />
                </div>
            </div>

            <!-- Average Rating -->
            <div class="flex justify-between bg-darkVariant/50 px-8 py-4 rounded-b-xl items-center">
                <div class="font-bold flex gap-8 items-center">
                    <div>{{ game.avg_rating?.toFixed(1) ?? '-' }} ({{ game.rating_count }})</div>
                    <admin-bar
                        v-if="page.props.auth.user.role.name.toLowerCase() === 'admin' || page.props.auth.user.role.name.toLowerCase() === 'moderator'"
                        :user="page.props.auth.user" :resource="game" type="games" />
                </div>
                <div>
                    <rating :value="rating" @update-rating="updateRating" size="text-2xl" />
                </div>
            </div>
        </article>
    </AppLayout>
</template>

<style scoped>
article {

    &:hover {
        & .article-header::before {
            clip-path: polygon(75% 0, 100% 0%, 100% 100%, 65% 100%);
        }
    }
}

.article-header::before {
    content: '';
    position: absolute;
    border-radius: inherit;
    inset: 0;
    background: var(--background);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    opacity: 0.25;
    filter: blur(5px);
    clip-path: polygon(35% 0, 100% 0%, 100% 100%, 25% 100%);
    transition: clip-path 0.3s ease;
}
</style>
