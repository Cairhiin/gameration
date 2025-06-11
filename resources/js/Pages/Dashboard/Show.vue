<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Radar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
} from 'chart.js'
import DashboardCard from '@/Components/Custom/DashboardCard.vue';
import PrimaryButton from '@/Components/Custom/PrimaryButton.vue';
import Rating from '@/Components/Custom/Rating.vue';
import ShowUserInformation from './Partials/ShowUserInformation.vue';
import CircularProgressBar from '@/Components/Custom/CircularProgressBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import type { DashboardData, Genre, User } from '@/Types';
import type { PropType } from 'vue';

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
);

const { user, latestRatedGames, favoriteGenres, dashboardData } = defineProps({
    user: Object as PropType<User>,
    friends: Array as PropType<User[]>,
    latestRatedGames: Array as PropType<any>,
    latestRatedBooks: Array as PropType<any>,
    highestRatedGames: Array as PropType<any>,
    highestRatedBooks: Array as PropType<any>,
    favoriteGenres: Object as PropType<Genre>,
    dashboardData: Object as PropType<DashboardData>,
});

const data: any = {
    labels: Object.keys(favoriteGenres),
    datasets: [
        {
            label: 'Favorite Genres',
            data: Object.values(favoriteGenres),
            backgroundColor: 'rgba(179,181,198,0.2)',
            borderColor: 'rgba(179,181,198,1)',
            pointBackgroundColor: 'rgba(179,181,198,1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(179,181,198,1)',
        }
    ],
};

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: false,
        },
    },
    scales: {
        r: {
            pointLabels: {
                font: {
                    size: 15
                }
            },
            angleLines: {
                display: false
            },
            suggestedMin: 0,
            backgroundColor: 'rgba(0,255,255,0.2)',
            ticks: {
                precision: 0,
                showLabelBackdrop: false,
                color: 'rgba(255,255,255,1)',
                font: {
                    size: 18
                }
            },
        }
    },
}

ChartJS.defaults.font.size = 18;
ChartJS.defaults.color = '#D3D9D4';
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div
                    class="flex gap-2 items-center justify-between bg-gray-800 text-white py-2 px-3 rounded-lg text-sm">
                    <div>
                        <header>
                            <h3>Games Rated</h3>
                        </header>
                        <div class="text-light/50">{{ user.games_rated_count }}</div>
                    </div>
                    <div>
                        <div class="text-2xl text-light/50"><i class="fa fa-solid fa-gamepad"></i></div>
                    </div>
                </div>
                <div
                    class="flex gap-2 items-center justify-between bg-gray-800 text-white py-3 px-3 rounded-lg text-sm">
                    <div>
                        <header>
                            <h3>Avg Game Rating</h3>
                        </header>
                        <div class="text-light/50">{{ dashboardData.averageGameRating?.toFixed(1) }}</div>
                    </div>
                    <div>
                        <circular-progress-bar :percentage="(dashboardData.averageGameRating / 5) * 100"
                            :stroke-width="5" :size="35" :background-color="'#0e1929'" :progress-color="'#42bfdd'" />
                    </div>
                </div>
                <div
                    class="flex gap-2 items-center justify-between bg-gray-800 text-white py-3 px-3 rounded-lg text-sm">
                    <div>
                        <header>
                            <h3>Books Rated</h3>
                        </header>
                        <div class="text-light/50">{{ user.books_rated_count }}</div>
                    </div>
                    <div>
                        <div class="text-2xl text-light/50"><i class="fa fa-solid fa-book"></i></div>
                    </div>
                </div>

                <div
                    class="flex gap-2 items-center justify-between bg-gray-800 text-white py-2 px-3 rounded-lg text-sm">
                    <div>
                        <header>
                            <h3>Avg Book Rating</h3>
                        </header>
                        <div class="text-light/50">{{ dashboardData.averageBookRating?.toFixed(1) }}</div>
                    </div>
                    <div>
                        <div><circular-progress-bar :percentage="(dashboardData.averageBookRating / 5) * 100"
                                :stroke-width="5" :size="35" :background-color="'#0e1929'"
                                :progress-color="'#42bfdd'" />
                        </div>
                    </div>
                </div>
            </section>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Newest Rated Books -->
                <dashboard-card>
                    <template #title>Latest Rated Books</template>
                    <template #content>
                        <ul v-if="latestRatedBooks.length">
                            <li v-for="ratedBook in latestRatedBooks" :key="ratedBook.id"
                                class="flex justify-between gap-2 items-center"><span class="truncate">{{
                                    ratedBook.book.title }}</span>
                                <rating :value="ratedBook.rating" :rateable="false" size="text-xl" />
                            </li>
                        </ul>
                        <div v-else class="text-center">No books have been rated yet!</div>
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4"
                            @click="router.visit('/books?sortBy=released_at')">Show
                            More</primary-button>
                    </template>
                </dashboard-card>

                <!-- Highest Rated Books -->
                <dashboard-card>
                    <template #title>Highest Rated Books</template>
                    <template #content>
                        <ul v-if="highestRatedBooks.length">
                            <li v-for="ratedBook in highestRatedBooks" :key="ratedBook.id"
                                class="flex justify-between gap-2 items-center"><span class="truncate">{{
                                    ratedBook.book.title }}</span>
                                <rating :value="ratedBook.rating" :rateable="false" size="text-xl" />
                            </li>
                        </ul>
                        <div v-else class="text-center">No books have been rated yet!</div>
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4"
                            @click="router.visit('/books?sortBy=avg_rating')">Show
                            More</primary-button>
                    </template>
                </dashboard-card>

                <!-- Newest Rated Games -->
                <dashboard-card>
                    <template #title>Latest Rated Games</template>
                    <template #content>
                        <ul v-if="latestRatedGames.length">
                            <li v-for="ratedGame in latestRatedGames" :key="ratedGame.id"
                                class="flex justify-between gap-2 items-center"><span class="truncate">{{
                                    ratedGame.game.name }}</span>
                                <rating :value="ratedGame.rating" :rateable="false" size="text-xl" />
                            </li>
                        </ul>
                        <div v-else class="text-center">No games have been rated yet!</div>
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4"
                            @click="router.visit('/games?sortBy=released_at')">Show
                            More</primary-button>
                    </template>
                </dashboard-card>

                <!-- Highest Rated Games -->
                <dashboard-card>
                    <template #title>Highest Rated Games</template>
                    <template #content>
                        <ul v-if="highestRatedGames.length">
                            <li v-for="ratedGame in highestRatedGames" :key="ratedGame.id"
                                class="flex justify-between gap-2 items-center"><span class="truncate">{{
                                    ratedGame.game.name }}</span>
                                <rating :value="ratedGame.rating" :rateable="false" size="text-xl" />
                            </li>
                        </ul>
                        <div v-else class="text-center">No games have been rated yet!</div>
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4"
                            @click="router.visit('/games?sortBy=avg_rating')">Show
                            More</primary-button>
                    </template>
                </dashboard-card>
            </div>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- User -->
                <dashboard-card>
                    <template #title>User Information</template>
                    <template #content>
                        <show-user-information :user="user" />
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4">Show More</primary-button>
                    </template>
                </dashboard-card>

                <!-- Most Rated Genres -->
                <dashboard-card v-if="Object.keys(favoriteGenres)?.length >= 5">
                    <template #title>Top Rated Genres</template>
                    <template #content>
                        <div class="flex justify-center">
                            <radar id="favorite-genres" :options="options" :data="data" />
                        </div>
                    </template>
                </dashboard-card>

                <!-- Friend List -->
                <dashboard-card>
                    <template #title>Friends</template>
                    <template #content>
                        <ul v-if="friends.length">
                            <li v-for="friend in friends" :key="friend.id">{{ friend.username }}</li>
                        </ul>
                        <div v-else>No friends yet!</div>
                    </template>
                    <template #buttons>
                        <primary-button variant="invert" class="mt-4"
                            @click="router.visit('/user/profile/friends')">Show
                            More</primary-button>
                    </template>
                </dashboard-card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.circular-progress {
    --progress: 20;
    --size: 250px;
    --half-size: calc(var(--size) / 2);
    --stroke-width: 20px;
    --radius: calc((var(--size) - var(--stroke-width)) / 2);
    --circumference: calc(var(--radius) * pi * 2);
    --dash: calc((var(--progress) * var(--circumference)) / 100);
}

.circular-progress circle {
    cx: var(--half-size);
    cy: var(--half-size);
    r: var(--radius);
    stroke-width: var(--stroke-width);
    fill: none;
    stroke-linecap: round;
}

.circular-progress circle.bg {
    stroke: #ddd;
}

.circular-progress circle.fg {
    transform: rotate(-90deg);
    transform-origin: var(--half-size) var(--half-size);
    stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
    transition: stroke-dasharray 0.3s linear 0s;
    stroke: #5394fd;
}
</style>
