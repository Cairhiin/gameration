<script setup>
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
import AppLayout from '@/Layouts/AppLayout.vue';

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
);

const { user, latestRatedGames, favoriteGenres } = defineProps({
    user: Object,
    latestRatedGames: Array,
    highestRatedGames: Array,
    favoriteGenres: Object
});

const data = {
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
    plugins: {
        legend: {
            display: false,
        }
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">

            <!-- Newest Rated -->
            <dashboard-card>
                <template #title>Latest Rated Games</template>
                <template #content>
                    <ul>
                        <li v-for="ratedGame in latestRatedGames" :key="ratedGame.id" class="flex justify-between">{{
                            ratedGame.game.name }}
                            <rating :value="ratedGame.rating" :rateable="false" size="text-xl" />
                        </li>
                    </ul>
                    <primary-button class="mt-8" @click="$inertia.visit('/games?sortBy=released_at')">Show
                        More</primary-button>
                </template>
            </dashboard-card>

            <!-- Highest Rated -->
            <dashboard-card>
                <template #title>Highest Rated Games</template>
                <template #content>
                    <ul>
                        <li v-for="ratedGame in highestRatedGames" :key="ratedGame.id" class="flex justify-between">{{
                            ratedGame.game.name }}
                            <rating :value="ratedGame.rating" :rateable="false" size="text-xl" />
                        </li>
                    </ul>
                    <primary-button class="mt-8" @click="$inertia.visit('/games?sortBy=avg_rating')">Show
                        More</primary-button>
                </template>
            </dashboard-card>

            <!-- User -->
            <dashboard-card>
                <template #title>User</template>
                <template #content>
                    <ul>
                        <li>{{ user.name }}</li>
                        <li>{{ user.email }}</li>
                        <li>{{ user.role.name }}</li>
                    </ul>
                    <primary-button class="mt-8">Show More</primary-button>
                </template>
            </dashboard-card>

            <!-- Most Rated Genres -->
            <dashboard-card class="md:col-span-2">
                <template #title>Top Rated Genres</template>
                <template #content>
                    <div class="flex justify-center">
                        <radar id="favorite-genres" :options="options" :data="data" />
                    </div>
                </template>
            </dashboard-card>
        </div>
    </AppLayout>
</template>
