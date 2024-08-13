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
            angleLines: {
                display: false
            },
            suggestedMin: 0,
            backgroundColor: 'rgba(0,255,255,0.2)',
            ticks: {
                precision: 0,
                showLabelBackdrop: false,
                color: 'rgba(255,255,255,1)',
            }
        }
    }
}

ChartJS.defaults.font.size = 18;
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            <dashboard-card>
                <template #title>Latest Rated Games</template>
                <template #content>
                    <ul>
                        <li v-for="ratedGame in latestRatedGames" :key="ratedGame.id">{{ ratedGame.game.name }}
                            <span>{{
                            ratedGame.rating }}</span>
                        </li>
                    </ul>
                    <primary-button class="mt-8">Show More</primary-button>
                </template>
            </dashboard-card>
            <dashboard-card>
                <template #title>Highest Rated Games</template>
                <template #content>
                    <ul>
                        <li v-for="ratedGame in highestRatedGames" :key="ratedGame.id">{{ ratedGame.game.name }}
                            <span>{{
                            ratedGame.rating }}</span>
                        </li>
                    </ul>
                    <primary-button class="mt-8">Show More</primary-button>
                </template>
            </dashboard-card>
            <dashboard-card>
                <template #title>Top Rated Genres</template>
                <template #content>
                    <radar id="favorite-genres" :options="options" :data="data" />
                    <primary-button class="mt-8">Show More</primary-button>
                </template>
            </dashboard-card>
            <dashboard-card>
                <template #title>User</template>
                <template #content>
                    <ul>
                        <li>{{ user.name }}</li>
                        <li>{{ user.email }}</li>
                        <li>{{ user.role.name }}</li>
                    </ul>
                </template>
            </dashboard-card>
        </div>
    </AppLayout>
</template>
