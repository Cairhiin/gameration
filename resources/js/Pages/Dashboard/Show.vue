<script lang="ts" setup>
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
import AppLayout from '@/Layouts/AppLayout.vue';
import type { Data, Game, Genre, User } from '@/Types';
import type { PropType } from 'vue';

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
);

const { user, latestRatedGames, favoriteGenres } = defineProps({
    user: Object as PropType<User>,
    latestRatedGames: Object as PropType<any>,
    highestRatedGames: Object as PropType<any>,
    favoriteGenres: Object as PropType<Genre>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Newest Rated -->
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
                    <primary-button class="mt-8" @click="$inertia.visit('/games?sortBy=released_at')">Show
                        More</primary-button>
                </template>
            </dashboard-card>

            <!-- Highest Rated -->
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
                    <primary-button class="mt-8" @click="$inertia.visit('/games?sortBy=avg_rating')">Show
                        More</primary-button>
                </template>
            </dashboard-card>

            <!-- User -->
            <dashboard-card>
                <template #title>User</template>
                <template #content>
                    <show-user-information :user="user" />
                </template>
                <template #buttons>
                    <primary-button class="mt-8">Show More</primary-button>
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
            <dashboard-card v-if="user.friends?.length">
                <template #title>User</template>
                <template #content>
                    <ul>
                        <li v-for="friend in user.friends" :key="friend.id">{{ friend.username }}</li>
                    </ul>
                </template>
                <template #buttons>
                    <primary-button class="mt-8">Show More</primary-button>
                </template>
            </dashboard-card>
        </div>
    </AppLayout>
</template>
