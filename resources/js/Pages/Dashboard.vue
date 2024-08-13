<script setup>
import SubHeader from '@/Components/Custom/SubHeader.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const { user, latestRatedGames, favoriteGenres } = defineProps({
    user: Object,
    latestRatedGames: Array,
    highestRatedGames: Array,
    favoriteGenres: Array
});
console.log(favoriteGenres)
</script>

<template>
    <AppLayout title="Dashboard">
        <div>
            <div class="flex gap-12">
                <section>
                    <sub-header level="h3">Latest Rated Games</sub-header>
                    <ul>
                        <li v-for="ratedGame in latestRatedGames" :key="ratedGame.id">{{ ratedGame.game.name }} <span>{{
                            ratedGame.rating }}</span></li>
                    </ul>
                    <button>Show More</button>
                </section>
                <section>
                    <sub-header level="h3">Highest Rated Games</sub-header>
                    <ul>
                        <li v-for="ratedGame in highestRatedGames" :key="ratedGame.id">{{ ratedGame.game.name }}
                            <span>{{
                            ratedGame.rating }}</span>
                        </li>
                    </ul>
                    <button>Show More</button>
                </section>
                <section>
                    <sub-header level="h3">Top Rated Genres</sub-header>
                    <ul>
                        <li v-for="value, key in favoriteGenres" :key="key">{{ key }}
                            <span>{{
                            value
                        }}</span>
                        </li>
                    </ul>
                    <button>Show More</button>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
