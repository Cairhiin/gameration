<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { capitalize } from '@/Utils/index.ts';

const { genres } = defineProps({
    genres: Array
});

const showGenre = (id) => {
    router.get(route('genres.show', id));
};
</script>

<template>
    <app-layout title="Genres">
        <section class="backdrop-blur-sm">
            <table class="table-fixed w-full border-separate border border-darkVariant">
                <thead class="bg-highlight/50 text-sm uppercase">
                    <td class="px-2 py-4 w-1/2">
                        Genre
                    </td>
                    <td class="text-center">
                        Average Rating
                    </td>
                    <td class="text-center">
                        Number of Games
                    </td>
                </thead>
                <tr v-for="genre in genres" :key="genre.id" @click="showGenre(genre.id)"
                    class="w-full p-4 my-2 rounded-md odd:bg-darkVariant/25 even:bg-darkVariant/50 hover:bg-lightVariant/15 cursor-pointer">
                    <td class="p-2">
                        {{
                    capitalize(genre.name) }}
                    </td>
                    <td class="text-center">{{ genre.avg_rating.toFixed(1)
                        }}</td>
                    <td class="text-center">{{ genre.games_count
                        }}</td>
                </tr>
            </table>
        </section>
    </app-layout>
</template>
