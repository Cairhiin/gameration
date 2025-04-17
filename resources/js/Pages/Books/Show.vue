<script lang="ts" setup>
import { computed, ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import type { InertiaPageProps } from '@/Types/inertia';
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
import ShowReviews from '@/Pages/Games/Partials/ShowReviews.vue';
import GameInfoCard from '@/Components/Custom/GameInfoCard.vue';
import ShowPageSection from '@/Components/Custom/ShowPageSection.vue';
import type { Rating as RatingType, Review, Data, Book } from '@/Types';
import type { PropType } from 'vue';
import { canModerate } from '@/Utils';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const page = usePage<InertiaPageProps>();

const isUserModerator: boolean = canModerate(page.props.auth.user);

const { book, rating, last_user_ratings, reviews, user_review } = defineProps({
    book: Object as PropType<Book>,
    rating: Number as PropType<number>,
    last_user_ratings: Array as PropType<RatingType[]>,
    reviews: Object as PropType<Data<Review>>,
    user_review: Object as PropType<Review | null>
});

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
    book.user_ratings.forEach(u => rates[u.rating * 2] += 1);
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

const bookImage = computed<string>(() => book.image ? `/storage/${book.image}` : image);

const updateRating = (value: number): void => {
    router.post(`/books/${book.id}/rate`, { rating: value * 5 / 100 }, {
        replace: true, preserveState: false,
        onSuccess: (res: any) => {
            console.log(res)
            lastUserRatings.value = [...res.props.last_user_ratings.map((r: any) => {
                return {
                    'rating': r.rating, 'user': r.user, 'id': r.book_id + r.user_id
                }
            })];
            loading.value = false;
        },
    });
};
</script>

<template>
    <AppLayout :title="book.title">
        <div :style="{ backgroundImage: `url(${bookImage})` }"
            class="relative bg-cover bg-center bg-no-repeat h-96 mb-4 md:mb-8">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-dark/75 to-dark">

            </div>
        </div>
        <article class="rounded-xl max-w-5xl mx-auto mt-8 px-4 md:px-8">

            <section id="book__info" class="grid grid-cols-4 gap-8 text-sm mb-4 md:mb-8">
                <game-info-card :book="book" heading="Release Date" icon="fa-calendar" :value="book.published_at"
                    :precision="0" />

                <game-info-card :book="book" heading="Rating Count" icon="fa-hashtag" :value="book.rating_count"
                    :precision="0" />

                <game-info-card :book="book" heading="Median Rating" icon="fa-gauge-simple" :precision="1"
                    :value="book.median_rating" />

                <game-info-card :book="book" heading="Average Rating" icon="fa-gauge-simple" :value="book.avg_rating"
                    :precision="1" />
            </section>

            <section class="article-header relative overflow-hidden mb-4 md:mb-8">
                <div>
                    <h4 class="font-bold uppercase text-default text-lightVariant">{{ book.series?.title }} book {{
                        book.series_book_number }}</h4>
                    <h3 class="relative font-bold uppercase text-5xl text-light">{{ book.title }}</h3>
                    <p>
                        <span v-for="a in book.authors" :key="a.id">
                            <Link :href="route('persons.show', a.id)">{{ a.name }}</Link>
                        </span>
                    </p>
                    <div class="relative flex gap-2 my-2">
                        <template v-for="genre in book.genres" :key="genre.id">
                            <tag size="small">{{ genre.name }}</tag>
                        </template>
                    </div>
                </div>
            </section>

            <section
                class="flex justify-between items-center py-4 px-6 gap-2 bg-darkVariant/25 rounded-3xl w-60 mb-4 md:mb-8">
                <rating :value="book.avg_rating" size="text-3xl" :rateable="false" />
                <div class="font-bold bg-dark">{{ book.avg_rating?.toFixed(1) ?? 0.0 }}</div>
            </section>

            <show-page-section>
                <template #header>Description</template>
                <template #content>
                    <div class="flex justify-between gap-12">
                        <div class="md:basis-3/4">
                            <div v-html="book.description" class="markdown"></div>
                        </div>
                        <div class="hidden md:block basis-1/4 max-w-80">
                            <div id="book__image">
                                <img :src="bookImage" :alt="book.title" class="object-cover w-full">
                                <p v-if="isUserModerator"
                                    class="text-center text-sm uppercase m-2 text-lightVariant hover:text-light">
                                    <Link :href="route('books.image.edit', book)">Edit Image</Link>
                                </p>
                            </div>
                        </div>
                    </div>
                </template>
            </show-page-section>

            <show-page-section>
                <template #header>Information</template>
                <template #content>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="uppercase font-bold text-base text-lightVariant mt-3" v-if="book.series">
                                Series
                            </h4>
                            <p>
                                <Link :href="route('books.series.show', book.series)">{{ book.series?.title }}</Link>
                            </p>
                        </div>
                        <div>
                            <h4 class="uppercase font-bold text-base text-lightVariant mt-3">
                                Publisher
                            </h4>
                            <p class="hover:underline">
                                <Link :href="route('publishers.show', book.publisher)">{{ book.publisher?.name }}</Link>
                            </p>
                        </div>
                        <div>
                            <h4 class="uppercase font-bold text-base text-lightVariant mt-3">Publishing Date</h4>
                            <p>{{ new Date(book.published_at).toLocaleDateString() }}</p>
                        </div>
                    </div>
                </template>
            </show-page-section>

            <!-- Last 10 Ratings -->
            <show-page-section>
                <template #header>Newest Ratings</template>
                <template #content>
                    <ul class="py-4" v-if="lastUserRatings.length">
                        <li v-for="rating in lastUserRatings" :key="rating.user_id"
                            class="flex gap-2 justify-between items-center odd:bg-dark-box/10 even:bg-dark-highlight-variant/5 px-2 py-1">
                            {{
                                rating.user?.username
                            }}
                            <rating :value="rating.rating" size="text-xl" :rateable="false" />
                        </li>
                    </ul>
                    <p v-else class="m-2 text-light">
                        No ratings yet.
                    </p>
                </template>
            </show-page-section>

            <!-- Rating Breakdown -->
            <show-page-section>
                <template #header>Rating Breakdown</template>
                <template #content>
                    <div class="py-4 h-62 flex justify-left">
                        <Bar :data="data" :options="barOptions" class="bg-dark p-8 rounded-lg" />
                    </div>
                </template>
            </show-page-section>

            <!-- Reviews
            <show-reviews :reviews="reviews" :review="user_review" :book="book" />-->

            <!-- Average Rating -->
            <section class="flex justify-between px-8 py-4 rounded-b-xl items-center">
                <div class="font-bold flex gap-8 items-center">
                    <div>{{ book.avg_rating?.toFixed(1) ?? '-' }} ({{ book.rating_count }})</div>
                    <admin-bar v-if="isUserModerator" :user="page.props.auth.user" :resource="book" type="books" />
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

.markdown>>>p {
    margin: 0 0 1rem 0;
    text-wrap: pretty;
    overflow-wrap: break-word;
    line-height: 1.7;
}
</style>
