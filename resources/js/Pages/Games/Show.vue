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
const isModerator = page.props.auth.user.roles.filter(role => role.name.includes('moderator')).length > 0
    || page.props.auth.user.roles.filter(role => role.name.includes('admin')).length > 0;

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
                backgroundColor: '#10B981',
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
        <article class="rounded-xl backdrop-blur-sm shadow-dark-sm">
            <div class="article-header relative rounded-t-xl overflow-hidden" :class="game.image ?? 'bg-dots-darker'">
                <div class="p-8 bg-gradient-to-r from-dark-box-40 via-dark-box-40 to-transparent">
                    <h3 class="relative font-bold uppercase text-2xl text-lightVariant">{{ game.name }}</h3>
                    <div class="relative flex gap-2 my-2">
                        <template v-for="genre in game.genres" :key="genre.id">
                            <tag size="small">{{ genre.name }}</tag>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex justify-between gap-12 p-8 bg-darkVariant/25">
                <div class="basis-3/4">
                    <div>{{ game.description }}</div>
                </div>
                <div class="basis-1/4">
                    <div id="game__image">
                        <img :src="gameImage" :alt="game.name" class="object-cover w-full">
                        <p>
                            <Link :href="route('games.image.edit', game)">Edit Image</Link>
                        </p>
                    </div>
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
            <div class=" bg-darkVariant/25 px-8 py-4 border-t border-lightVariant/15">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Newest Ratings</h3>
                <ul class="py-4">
                    <li v-for="rating in lastUserRatings"
                        :key="rating.game_id ? rating.game_id + rating.rating : rating.id"
                        class="flex gap-2 justify-between items-center odd:bg-dark-box/10 even:bg-dark-highlight-variant/5 px-2 py-1">
                        {{
                            rating.user?.username
                        }}
                        <rating :value="rating.rating" size="text-xl" :rateable="false" />
                    </li>
                </ul>
            </div>

            <!-- Rating Breakdown -->
            <div class=" bg-darkVariant/25 px-8 py-4 border-t border-lightVariant/15">
                <h3 class="text-dark-highlight-variant font-bold uppercase text-sm py-4">Rating Breakdown</h3>
                <div class="py-4 h-62 flex justify-left">
                    <Bar :data="data" :options="barOptions" class="bg-dark p-8 rounded-lg" />
                </div>
            </div>

            <!-- Average Rating -->
            <div class="flex justify-between bg-dark-box/10 px-8 py-4 rounded-b-xl items-center">
                <div class="font-bold flex gap-8 items-center">
                    <div>{{ game.avg_rating?.toFixed(1) ?? '-' }} ({{ game.rating_count }})</div>
                    <admin-bar v-if="isModerator" :user="page.props.auth.user" :resource="game" type="games" />
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

.bg-dots-darker {
    background-image: url("data:image/svg+xml,<svg id='patternId' opacity='0.03' width='100%' height='100%' xmlns='http://www.w3.org/2000/svg'><defs><pattern id='a' patternUnits='userSpaceOnUse' width='80' height='46.185' patternTransform='scale(1) rotate(0)'><rect x='0' y='0' width='100%' height='100%' fill='hsla(240,6.7%,17.6%,1)'/><path d='M0 .002V13.63l9.87-5.69L21.944.975l7.373 4.239C19.536 10.843 9.782 16.503 0 22.135v24.05h.842V23.557l.145-.087C10.71 17.868 20.436 12.266 30.13 6.664v8.476c-4.006 2.35-8.041 4.673-12.076 6.995-3.28 1.887-6.56 3.803-9.869 5.69v18.359H9.87v-6.022a1928.14 1928.14 0 0 1 10.396 6.023h3.338l-1.659-.972c-4.005-2.323-8.04-4.645-12.075-6.967V29.74l28.45 16.445h3.389c9.454-5.482 18.938-10.963 28.421-16.444v8.505a4590.557 4590.557 0 0 1-12.076 6.967l-1.63.972h3.285v-.016c3.483-2.004 6.966-4.006 10.421-6.01v6.024h1.684v-18.36l-6.01-3.483-3.86-2.206a2547.983 2547.983 0 0 1-12.076-6.996V6.662c9.754 5.631 19.536 11.263 29.29 16.894v22.627H80v-24.05L50.682 5.211 58.055.972 70.131 7.94 80 13.63V0h-.842v11.22l-7.344-4.24V0h-1.683v6.023L59.735 0h-3.36l-8.189 4.717v25.37l-7.344 4.238V.48l.146-.087L41.63 0h-3.26l.788.48v33.845l-7.344-4.238V4.717l-6.008-3.454L23.653 0h-3.387L9.87 6.023V0H8.186v6.982L.842 11.22V0zm30.13 17.083v12.017l-10.42-6.009c3.483-2.003 6.966-4.006 10.42-6.008zm19.74 0c3.483 2.002 6.937 4.005 10.42 6.008l-10.42 6.009zm-31.814 6.967 12.075 6.995 9.87 5.69 9.868-5.69 12.076-6.995 7.373 4.237c-9.782 5.66-29.317 16.924-29.317 16.924L10.683 28.289z'  stroke-width='1' stroke='none' fill='hsla(47,80.9%,61%,1)'/></pattern></defs><rect width='800%' height='800%' transform='translate(0,0)' fill='url(%23a)'/></svg>")
}
</style>
