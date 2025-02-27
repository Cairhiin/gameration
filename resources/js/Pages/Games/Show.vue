<script lang="ts" setup>
import { computed, ref, watch } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import type { InertiaPageProps } from '@/Types/inertia';
import AppLayout from '@/Layouts/AppLayout.vue';
import image from '../../../images/missing_image_light.png';
import Tag from '@/Components/Custom/Tag.vue';
import Rating from '@/Components/Custom/Rating.vue';
import AdminBar from '@/Components/Custom/AdminBar.vue';
import GameInfoCard from '@/Components/Custom/GameInfoCard.vue';
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
import ShowReviews from './Partials/ShowReviews.vue';
import type { Rating as RatingType, Game, Review, Data } from '@/Types';
import type { PropType } from 'vue';
import { canModerate } from '@/Utils';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const page = usePage<InertiaPageProps>();

const isUserModerator: boolean = canModerate(page.props.auth.user);

const { game, rating, last_user_ratings, reviews, user_review } = defineProps({
    game: Object as PropType<Game>,
    rating: Number as PropType<number>,
    last_user_ratings: Array as PropType<RatingType[]>,
    reviews: Object as PropType<Data<Review>>,
    user_review: Object as PropType<Review | null>
});
console.log(last_user_ratings);
const ratingsByScore = computed<{ 1: number, 2: number, 3: number, 4: number, 5: number, 6: number, 7: number, 8: number, 9: number, 10: number }>(() => {
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
    game.user_ratings.forEach(u => rates[u.rating * 2] += 1);
    return rates;
});

const data = computed(() => {
    return {
        labels: Object.keys(ratingsByScore.value).map((key: string): number => Number(key) / 2),
        datasets: [
            {
                label: 'Ratings',
                data: Object.values(ratingsByScore.value),
                yAxisId: 'y',
                xAxisId: 'x',
                backgroundColor: '#42bfdd',
                maxBarThickness: 24,
                borderRadius: 4
            }
        ],
    };
});

const lastUserRatings = ref<RatingType[]>(last_user_ratings);
const loading = ref<boolean>(false);

const gameImage = computed<string>(() => game.image ? `/storage/${game.image}` : image);

const updateRating = (value: number): void => {
    router.post(`/games/${game.id}/rate`, { rating: value * 5 / 100 }, {
        replace: true, preserveState: false,
        onSuccess: (res: any) => {
            lastUserRatings.value = [...res.props.last_user_ratings.map(r => {
                return {
                    'rating': r.rating, 'user': r.user, 'id': r.game_id + r.user_id
                }
            })];
            loading.value = false;
        },
    });
};
</script>

<template>
    <AppLayout :title="game.name">
        <article class="rounded-xl max-w-5xl mx-auto">
            <section id="game__info" class="grid grid-cols-4 gap-8 text-sm mb-6">
                <game-info-card :game="game" heading="Release Date" icon="fa-calendar" :value="game.released_at"
                    :precision="0" />

                <game-info-card :game="game" heading="Rating Count" icon="fa-hashtag" :value="game.rating_count"
                    :precision="0" />

                <game-info-card :game="game" heading="Median Rating" icon="fa-gauge-simple" :precision="1"
                    :value="game.median_rating" />

                <game-info-card :game="game" heading="Average Rating" icon="fa-gauge-simple" :value="game.avg_rating"
                    :precision="1" />
            </section>

            <section class="article-header relative overflow-hidden">
                <div class="p-4">
                    <h3 class="relative font-bold uppercase text-5xl text-light">{{ game.name }}</h3>
                    <div class="relative flex gap-2 my-2">
                        <template v-for="genre in game.genres" :key="genre.id">
                            <tag size="small">{{ genre.name }}</tag>
                        </template>
                    </div>
                </div>
            </section>

            <section class="flex justify-between items-center py-4 px-6 gap-2 bg-darkVariant/25 rounded-xl w-60 my-6">
                <rating :value="game.avg_rating" size="text-3xl" :rateable="false" />
                <div class="font-bold">{{ game.avg_rating?.toFixed(1) ?? 0.0 }}</div>
            </section>

            <section class="p-8 bg-darkVariant/25 rounded-xl">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Description</h3>
                <div class="flex justify-between gap-12">
                    <div class="md:basis-3/4">
                        <div>{{ game.description }}</div>
                    </div>
                    <div class="hidden md:block basis-1/4 max-w-80">
                        <div id="game__image">
                            <img :src="gameImage" :alt="game.name" class="object-cover w-full">
                            <p v-if="isUserModerator"
                                class="text-center text-sm uppercase m-2 text-lightVariant hover:text-light">
                                <Link :href="route('games.image.edit', game)">Edit Image</Link>
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-darkVariant/25 px-8 py-4 my-6 rounded-xl">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Information</h3>
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
            </section>

            <!-- Last 10 Ratings -->
            <section class=" bg-darkVariant/25 px-8 py-4 my-6 rounded-xl">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Newest Ratings</h3>
                <ul class="py-4">
                    <li v-for="rating in lastUserRatings" :key="rating.user_id"
                        class="flex gap-2 justify-between items-center odd:bg-dark-box/10 even:bg-dark-highlight-variant/5 px-2 py-1">
                        {{
                            rating.user?.username
                        }}
                        <rating :value="rating.rating" size="text-xl" :rateable="false" />
                    </li>
                </ul>
            </section>

            <!-- Rating Breakdown -->
            <section class=" bg-darkVariant/25 px-8 py-4 my-6 rounded-xl">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Rating Breakdown</h3>
                <div class="py-4 h-62 flex justify-left">
                    <Bar :data="data" :options="barOptions" class="bg-dark p-8 rounded-lg" />
                </div>
            </section>

            <!-- Reviews -->
            <show-reviews :reviews="reviews" :review="user_review" :game="game" />

            <!-- Average Rating -->
            <section class="flex justify-between px-8 py-4 rounded-b-xl items-center">
                <div class="font-bold flex gap-8 items-center">
                    <div>{{ game.avg_rating?.toFixed(1) ?? '-' }} ({{ game.rating_count }})</div>
                    <admin-bar v-if="isUserModerator" :user="page.props.auth.user" :resource="game" type="games" />
                </div>
                <div>
                    <rating :value="rating" @update-rating="updateRating" size="text-2xl" />
                </div>
            </section>

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
